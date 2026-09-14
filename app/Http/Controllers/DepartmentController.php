<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Show all departments
    public function index()
    {
        $departments = Department::latest()->get();

        return view('departments.index', compact('departments'));
    }


    // Show create form
    public function create()
    {
        return view('departments.create');
    }


    // Store department
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect('/departments')
            ->with('success', 'Department created successfully!');
    }


    // Show edit form
    public function edit($id)
    {
        $department = Department::findOrFail($id);

        return view('departments.edit', compact('department'));
    }


    // Update department
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect('/departments')
            ->with('success', 'Department updated successfully!');
    }


    // Delete department
    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        $department->delete();

        return redirect('/departments')
            ->with('success', 'Department deleted successfully!');
    }
}