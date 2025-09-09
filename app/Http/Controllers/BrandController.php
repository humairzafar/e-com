<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands= Brand::all();
        return view('e-com.brands.index', compact('brands'));
    }
    public function store (Request $request)
    {
        $validated = $request->validate([
        'name'      => 'required|string|max:255|unique:brands,name',
        'slug'      => 'required|string|max:255|unique:brands,slug',
        'is_active' => 'required|boolean',
    ]);
        Brand::create($validated);
        return $this->getLatestRecords('Brand created successfully');
    }
    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:brands,id']);
        $brand = Brand::findOrFail($request->id);
        return response()->json($brand);
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id'        => 'required|exists:brands,id',
            'name'      => 'required|string|max:255|unique:brands,name,' . $request->id,
            'slug'      => 'required|string|max:255|unique:brands,slug,' . $request->id,
            'is_active' => 'required|boolean',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->update($validated);

        return $this->getLatestRecords('Brand updated successfully');
    }
    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:brands,id']);
        $brand = Brand::findOrFail($request->id);
        $brand->delete();
        return $this->getLatestRecords('Brand deleted successfully');
    }
    public function getLatestRecords($message = 'Brands fetched successfully')
    {
        $brands = Brand::all();
        return response()->json([
            'success' => true,
            'html' => view('e-com.brands.data-table', compact('brands'))->render(),
            'message' => $message,
        ]);
    }
}
