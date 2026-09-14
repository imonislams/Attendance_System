<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Employee List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $employees = Employee::with('department')
            ->latest()
            ->get();

        return view(
            'employees.index',
            compact('employees')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Employee Page
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $departments = Department::orderBy('name')
            ->get();

        return view(
            'employees.create',
            compact('departments')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Employee
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:employees,email',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'employee_code' => [
                'required',
                'string',
                'max:50',
                'unique:employees,employee_code',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'joining_date' => [
                'required',
                'date',
            ],

        ]);


        Employee::create($validated);


        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Employee created successfully!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Employee Page
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        $departments = Department::orderBy('name')
            ->get();

        return view(
            'employees.edit',
            compact(
                'employee',
                'departments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Employee
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $employee = Employee::findOrFail($id);


        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:employees,email,' . $employee->id,
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'employee_code' => [
                'required',
                'string',
                'max:50',
                'unique:employees,employee_code,' . $employee->id,
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'joining_date' => [
                'required',
                'date',
            ],

        ]);


        $employee->update($validated);


        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Employee updated successfully!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Employee
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();


        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Employee deleted successfully!'
            );
    }
}