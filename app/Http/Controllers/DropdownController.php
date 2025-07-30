<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function getAllCategories(Request $request){
        $categories = Category::where('user_id',auth()->user()->id)->where('is_active', 1)->get();
        return response()->json(['categories' => $categories]);
    }

    public function getAllSubCategories(Request $request)
    {
            $request->validate([
                'category_id' => 'nullable|integer|exists:categories,id'
            ]);

            $query = SubCategory::query();

            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            $subCategories = $query->where('user_id',auth()->user()->id)->with('category')->get();

            if ($subCategories->isEmpty()) {
                return response()->json([
                    'message' => 'No subcategories found',
                    'subCategories' => []
                ], 200);
            }

            return response()->json(['subCategories' => $subCategories]);


    }
}
