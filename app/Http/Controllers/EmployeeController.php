<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $departments=Department::Where('is_active','1')->get();
        $designations=Designation::Where('is_active','1')->get();
        $employees=Employee::with(['Department','Designation'])->get();
        return view('e-com.employee.index', compact('employees','designations','departments'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255|',
            'lastname' => 'required|string|max:255|',
            'cnic' => ['required', 'digits:13', 'unique:employees,cnic'],
            'dob' => 'required|date|max:255|',
            'doj' => 'required|date|max:255|',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'image' => 'nullable|image',
            'is_active' => 'required|boolean',
        ]);

        if($request->hasFile('image'))
        {
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $filename= time(). '.'.$extension;
            $path='upload/images/';
            $file->move(public_path($path), $filename);
            $validated['image'] = $path . $filename;
        }

        Employee::create($validated);
        return $this->getLatestRecords('Employee Added successfully');
    }
    public function edit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employees,id'
        ]);

        $employees = Employee::findOrFail($request->id);

        return response()->json($employees);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:employees,id',
            'firstname' => 'required|string|max:255|unique:employees,firstname,' . $request->id,
            'lastname' => 'required|string|max:255|',
            'cnic' => ['required', 'digits:13'],
            'dob' => 'required|date|max:255|',
            'doj' => 'required|date|max:255|',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'image' => 'nullable|image',
            'is_active' => 'required|boolean',
        ]);
        if($request->hasFile('image'))
        {
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $filename= time(). '.'.$extension;
            $path='upload/images/';
            $file->move(public_path($path), $filename);
            $validated['image'] = $path . $filename;
        }

        $employees = Employee::findOrFail($request->id);
        $employees->update($validated);

        return $this->getLatestRecords('Department Update Sucessfully !');
    }
    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:employees,id']);

        $employees = Employee::findOrFail($request->id);
        $employees->delete();

        return $this->getLatestRecords('Employee deleted successfully');
    }
    private function getLatestRecords($message = 'Employee fetched successfully')
    {
       $employees = Employee::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.employee.data-table', compact('employees'))->render(),
            'message' => $message,
        ]);
    }
}
