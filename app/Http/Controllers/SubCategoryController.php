<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


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


    public function exportCsv()
{
    $subCategories = \App\Models\SubCategory::with('category')->get();

    $filename = "subcategories.csv";
    $handle = fopen($filename, 'w');

    // Header row
    fputcsv($handle, ['SubCategory Name', 'Slug', 'Category Name', 'Is Active', 'User ID']);

    foreach ($subCategories as $sub) {
        fputcsv($handle, [
            $sub->name,
            $sub->slug,
            $sub->category ? $sub->category->name : '',
            $sub->is_active,
            $sub->user_id,
        ]);
    }

    fclose($handle);

    return response()->download($filename)->deleteFileAfterSend(true);
}
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt'
    ]);

    $file = $request->file('file');
    $handle = fopen($file->getRealPath(), 'r');

    $header = true;
    $count = 0;

    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
        if ($header) {
            $header = false;
            continue;
        }


        $category = \App\Models\Category::where('name', trim($row[2]))->first();

        if (!$category) {
            return back()->with('error', "Category '{$row[2]}' not found in DB. Import stopped.");
        }

        // Insert or update SubCategory by slug
        SubCategory::updateOrCreate(
            ['slug' => trim($row[1])], // condition (unique slug)
            [
                'name'        => trim($row[0]),
                'category_id' => $category->id,
                'is_active'   => $row[3] ?? 1,
                'user_id'     => $row[4] ?? auth()->id(),
            ]
        );

        $count++;
    }

    fclose($handle);

    return back()->with('success', "$count SubCategories imported/updated successfully.");
}

}

