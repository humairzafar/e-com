<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::where('user_id',auth()->user()->id)->with('category')->latest('created_at')->get();
        return view('e-com.sub-categories.index', compact('subCategories'));
    }
    public function store(Request $request){
      $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        $validated['user_id']=auth()->user()->id;
        if(SubCategory::create($validated)){
            return $this->getLatestRecords('Sub-Category created successfully');
        }
    }

   public  function edit(Request $request){
        $subCategory = SubCategory::with('category')->find($request->id);
        return response()->json([
            'success' => true,
            'data' => $subCategory,
        ]);
    }
    function update(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'required|boolean',
            'id' => 'required|exists:sub_categories,id',
        ]);
        $subCategory = SubCategory::findOrFail($request->id);
        $subCategory->update($validated);
        return $this->getLatestRecords('Sub-Category updated successfully');
    }



    private function getLatestRecords($message = 'Sub-Categories fetched successfully')
    {
        $subCategories = SubCategory::where('user_id',auth()->user()->id)->with('category')->latest('created_at')->get();

        return response()->json([
            'success' => true,
            'html' => view('e-com.sub-categories.data-table', compact('subCategories'))->render(),
            'message' => $message,
        ]);
    }
    function destroy(Request $request){
        $subCategory = SubCategory::findOrFail($request->id);
        $subCategory->delete();
        return $this->getLatestRecords('Sub-Category deleted successfully');
    }
}

