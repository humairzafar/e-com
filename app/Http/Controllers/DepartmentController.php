<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::where('user_id', auth()->id())->get();
        return view('e-com.department.index', compact('departments'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'is_active' => 'required|boolean',
            'is_head_office_department' => 'required|boolean',
        ]);

        $validated['user_id'] = auth()->id();

        Department::create($validated);

        return $this->getLatestRecords('Department created successfully');
    }

    public function edit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:departments,id'
        ]);

        $department = Department::findOrFail($request->id);

        return response()->json($department);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255|unique:departments,name,' . $request->id,
            'is_active' => 'required|boolean',
            'is_head_office_department' => 'required|boolean',
        ]);

        $department = Department::findOrFail($request->id);
        $department->update($validated);

        return $this->getLatestRecords('Department Update Sucessfully !');
    }
    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:departments,id']);

        $department = Department::findOrFail($request->id);
        $department->delete();

        return $this->getLatestRecords('Department deleted successfully');
    }

    private function getLatestRecords($message = 'Department fetched successfully')
    {
        $departments = Department::where('user_id', auth()->id())->latest('created_at')->get();

        return response()->json([
            'success' => true,
            'html' => view('e-com.department.data-table', compact('departments'))->render(),
            'message' => $message,
        ]);
    }
}


