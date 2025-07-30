<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function select ()
    {
        $categories = Category::all();
        return view('category.index' , compact ('categories'));
    }
    public function store (Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:categories,slug',
         ]);
        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);
        return $this->getLatestCategory();
    }
    private function getLatestCategory()
    {
        return response()->json([
            'success' => true,
            'message' => 'Record Save Successfully',
            'html' => view('category.data-table', ['categories' => Category::all()])->render(),
        ]);

    }
    public function editCategory (Request $request)
    {
        $id =$request->id;
        $category = Category::find($id);
        if(!empty($category))
            {
                return response()->json($category);
            }
    }
}
