<?php

namespace App\Http\Controllers;
Use App\Models\Location;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations= Location::all();
        return view('e-com.Location.index' , compact('locations'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:categories,slug',
        ]);
        location::create($validated);

        return $this->getLatestRecords('location created successfully');
    }

    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:categories,id']);
        $Location = Location::findOrFail($request->id);

        return response()->json($Location);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255|unique:locations,name,'.$request->id,
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:locations,slug,'.$request->id,
        ]);

        $Location = Location::findOrFail($request->id);
        $Location->update($validated);

        return $this->getLatestRecords('Location updated successfully');
    }
    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:categories,id']);
        $locations = Location::findOrFail($request->id);
        $locations->delete();

        return $this->getLatestRecords('Location deleted successfully');
    }
    private function getLatestRecords($message = 'Employee fetched successfully')
    {
       $locations = Location::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.location.data-table', compact('locations'))->render(),
            'message' => $message,
        ]);
    }

}
