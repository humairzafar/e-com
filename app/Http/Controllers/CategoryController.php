<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->user()->id )->latest('created_at')->get();

        return view('e-com.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:categories,slug',
        ]);

        $validated['user_id']= auth()->user()->id;

        Category::create($validated);

        return $this->getLatestRecords('Category created successfully');
    }

    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:categories,id']);
        $category = Category::findOrFail($request->id);

        return response()->json($category);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:categories,name,'.$request->id,
            'is_active' => 'required|boolean',
            'slug' => 'required|string|max:255|unique:categories,slug,'.$request->id,
        ]);

        $category = Category::findOrFail($request->id);
        $category->update($validated);

        return $this->getLatestRecords('Category updated successfully');
    }

    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:categories,id']);
        $category = Category::findOrFail($request->id);
        $category->delete();

        return $this->getLatestRecords('Category deleted successfully');
    }

    private function getLatestRecords($message = 'Categories fetched successfully')
    {
        $categories = Category::where('user_id', auth()->user()->id)->latest('created_at')->get();

        return response()->json([
            'success' => true,
            'html' => view('e-com.categories.data-table', compact('categories'))->render(),
            'message' => $message,
        ]);
    }


}
