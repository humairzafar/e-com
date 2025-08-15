<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehiclesCategory;

class VehiclesCategoryController extends Controller
{
    public function index()
    {
        $vehiclecategories= VehiclesCategory::all();
        return view('e-com.vehiclecategory.index' , compact('vehiclecategories'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:categories,slug',
        ]);
        VehiclesCategory::create($validated);

        return $this->getLatestRecords('VehiclesCategory created successfully');
    }
    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:categories,id']);
        $vehiclesCategories = VehiclesCategory::findOrFail($request->id);

        return response()->json($vehiclesCategories);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255|unique:locations,name,'.$request->id,
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:locations,slug,'.$request->id,
        ]);

        $vehiclesCategories = VehiclesCategory::findOrFail($request->id);
        $vehiclesCategories->update($validated);

        return $this->getLatestRecords('vehiclesCategories updated successfully');
    }
     public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:categories,id']);
        $vehiclesCategories = VehiclesCategory::findOrFail($request->id);
        $vehiclesCategories->delete();

        return $this->getLatestRecords('Location deleted successfully');
    }

    private function getLatestRecords($message = 'Employee fetched successfully')
    {
       $vehiclecategories = VehiclesCategory::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.vehiclecategory.data-table', compact('vehiclecategories'))->render(),
            'message' => $message,
        ]);
    }

}
