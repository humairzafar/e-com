<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehiclesCategory;
use App\Models\Town;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
public function index()
{
    $categories = VehiclesCategory::all();
    $towns = Town::all();
    $vehicles = Vehicle::with(['town', 'category'])->get();

    return view('e-com.vehicle.index', compact('vehicles', 'categories', 'towns'));
}

public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|integer|max:255|unique:vehicles,vehicle_id',
            'category_id' => 'required|exists:vehicles_categories,id',
            'town_id' => 'required|exists:towns,id',
            'condition' => 'required|boolean',
            'status' => 'required|boolean',
        ]);
        Vehicle::create($validated);
        return $this->getLatestRecords('Vehicle created successfully');
    }
    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:vehicles,id']);
        $part =Vehicle::findOrFail($request->id);

        return response()->json($part);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|integer|max:255|unique:vehicles,vehicle_id',
            'category_id' => 'required|exists:vehicles_categories,id',
            'town_id' => 'required|exists:towns,id',
            'condition' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        $vehicles = Vehicle::findOrFail($request->id);
        $vehicles->update($validated);

        return $this->getLatestRecords('Vehicle updated successfully');
    }
    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:vehicles,id']);
        $vehicles = Vehicle::findOrFail($request->id);
        $vehicles->delete();
        return $this->getLatestRecords('Parts deleted successfully');
    }
    private function getLatestRecords($message = 'Vehicle fetched successfully')
    {
       $vehicles = Vehicle::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.vehicle.data-table', compact('vehicles'))->render(),
            'message' => $message,
        ]);
    }
}
