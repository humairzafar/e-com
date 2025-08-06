<?php

namespace App\Http\Controllers;
Use App\Models\Designation;

use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::where('user_id', auth()->id())->get();
        return view('e-com.designation.index', compact('designations'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
            'is_active' => 'required|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        Designation::create($validated);
        return $this->getLatestRecords('designation created successfully');
    }
    public function edit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:designations,id'
        ]);

        $designations = Designation::findOrFail($request->id);

        return response()->json($designations);
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:designations,id',
            'name' => 'required|string|max:255|unique:designations,name,' . $request->id,
            'is_active' => 'required|boolean',
        ]);

        $Designation = Designation::findOrFail($request->id);
        $Designation->update($validated);

        return $this->getLatestRecords('Designation Update Sucessfully !');
    }
    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:Designations,id']);

        $Designation = Designation::findOrFail($request->id);
        $Designation->delete();

        return $this->getLatestRecords('Designation deleted successfully');
    }

    private function getLatestRecords($message = 'designation fetched successfully')
    {
        $designations = Designation::where('user_id', auth()->id())->latest('created_at')->get();

        return response()->json([
            'success' => true,
            'html' => view('e-com.designation.data-table', compact('designations'))->render(),
            'message' => $message,
        ]);
    }
}
