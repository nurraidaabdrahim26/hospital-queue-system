<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:departments|max:255',
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')->with('success', 'Department created successfully!');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|unique:departments,name,' . $department->id . '|max:255',
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
    }

    public function destroy(Department $department)
    {
        // Check if department has users or queues
        if ($department->users()->exists() || $department->queues()->exists()) {
            return redirect()->route('departments.index')->with('error', 'Cannot delete department with existing staff or queues!');
        }

        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }
}