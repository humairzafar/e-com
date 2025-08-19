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

public function exportCsv()
{

    $filename = 'categories_' . now()->format('Y-m-d_H-i-s') . '.csv';
    $headers = [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        'Cache-Control'       => 'no-store, no-cache, must-revalidate',
        'Pragma'              => 'no-cache',
    ];
    return response()->streamDownload(function () {
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($out, ['id', 'name', 'slug', 'is_active', 'user_id']);
        Category::select('id', 'name', 'slug', 'is_active', 'user_id')
            ->orderBy('id')
            ->chunk(1000, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->id,
                        $row->name,
                        $row->slug,
                        $row->is_active ? 1 : 0,
                        $row->user_id,
                    ]);
                }
            });

        fclose($out);
    }, $filename, $headers);
}
public function import(Request $request)
{
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        foreach ($data as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $name     = $row[1] ?? null;
            $slug     = $row[2] ?? null;
            $isActive = $row[3] ?? 1;
            $userId   = $row[4] ?? 1;

            if (!$name || !$slug) continue;

            \App\Models\Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'      => $name,
                    'is_active' => (int)$isActive,
                    'user_id'   => (int)$userId,
                ]
            );
        }
    }

    return back()->with('success', 'Categories imported successfully!');
}



}
