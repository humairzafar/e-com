<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Town;

class TownController extends Controller
{
    public function index()
    {
        $towns= Town::all();
        return view('e-com.town.index' , compact('towns'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:towns,name',
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:towns,slug',
        ]);
        Town::create($validated);

        return $this->getLatestRecords('Town created successfully');
    }
    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:towns,id']);
        $towns = Town::findOrFail($request->id);

        return response()->json($towns);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:towns,id',
            'name' => 'required|string|max:255|unique:towns,name,'.$request->id,
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:towns,slug,'.$request->id,
        ]);

        $towns =town::findOrFail($request->id);
        $towns->update($validated);

        return $this->getLatestRecords('towns updated successfully');
    }
    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:towns,id']);
        $towns = Town::findOrFail($request->id);
        $towns->delete();
        return $this->getLatestRecords('Town deleted successfully');
    }
    private function getLatestRecords($message = 'town fetched successfully')
    {
       $towns = Town::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.town.data-table', compact('towns'))->render(),
            'message' => $message,
        ]);
    }
}
