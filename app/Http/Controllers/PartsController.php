<?php

namespace App\Http\Controllers;
use App\Models\Location;
use App\Models\Part;

use Illuminate\Http\Request;

class PartsController extends Controller
{
    public function index()
    {
        $locations= Location::All();
        $parts= Part::with('location')->get();
        return view('e-com.parts.index' , compact('locations', 'parts'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:parts,name',
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:parts,slug',
            'location_id' => 'required|exists:locations,id',
        ]);
        part::create($validated);
        return $this->getLatestRecords('part created successfully');
    }
    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:parts,id']);
        $part = Part::findOrFail($request->id);
        return response()->json($part);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:parts,id',
            'name' => 'required|string|max:255|unique:parts,name,'.$request->id,
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:parts,slug,'.$request->id,
            'location_id' => 'required|exists:locations,id',
        ]);

        $part = Part::findOrFail($request->id);
        $part->update($validated);

        return $this->getLatestRecords('part updated successfully');
    }
     public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:parts,id']);
        $parts = Part::findOrFail($request->id);
        $parts->delete();
        return $this->getLatestRecords('Parts deleted successfully');
    }
    private function getLatestRecords($message = 'Parts fetched successfully')
    {
       $parts = Part::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.parts.data-table', compact('parts'))->render(),
            'message' => $message,
        ]);
    }
}
