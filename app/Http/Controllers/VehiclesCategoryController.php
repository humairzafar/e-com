<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehiclesCategory;

class VehiclesCategoryController extends Controller
{
    public function index()
    {
        $vehiclecategories = VehiclesCategory::all();
        return view('e-com.vehiclecategory.index', compact('vehiclecategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vehicles_categories,name',
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:vehicles_categories,slug',
        ]);

        VehiclesCategory::create($validated);

        return $this->getLatestRecords('Vehicle category created successfully');
    }

    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:vehicles_categories,id']);
        $vehiclesCategory = VehiclesCategory::findOrFail($request->id);

        return response()->json($vehiclesCategory);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:vehicles_categories,id',
            'name' => 'required|string|max:255|unique:vehicles_categories,name,' . $request->id,
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:vehicles_categories,slug,' . $request->id,
        ]);

        $vehiclesCategory = VehiclesCategory::findOrFail($request->id);
        $vehiclesCategory->update($validated);

        return $this->getLatestRecords('Vehicle category updated successfully');
    }

    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:vehicles_categories,id']);
        $vehiclesCategory = VehiclesCategory::findOrFail($request->id);
        $vehiclesCategory->delete();

        return $this->getLatestRecords('Vehicle category deleted successfully');
    }

    private function getLatestRecords($message = 'Data fetched successfully')
    {
        $vehiclecategories = VehiclesCategory::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.vehiclecategory.data-table', compact('vehiclecategories'))->render(),
            'message' => $message,
        ]);
    }
}
