<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Employee List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $employees = Employee::with(['department', 'shift', 'user'])
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
        $departments = Department::orderBy('name')->get();
        $shifts = Shift::orderBy('name')->get();

        return view(
            'employees.create',
            compact('departments', 'shifts')
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
                'unique:users,email',
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
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'department_id' => [
                'required',
                'exists:departments,id',
            ],
            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],
            'shift_id' => [
                'required',
                'exists:shifts,id',
            ],
            'joining_date' => [
                'required',
                'date',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            // Create User account for Employee
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'employee',
            ]);

            // Create Employee record
            Employee::create([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'],
                'shift_id' => $validated['shift_id'],
                'designation' => $validated['designation'] ?? null,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'employee_code' => $validated['employee_code'],
                'joining_date' => $validated['joining_date'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee account created successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | Show Employee
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $employee = Employee::with(['department', 'shift', 'user', 'attendances' => function($q) {
            $q->latest('attendance_date')->take(30);
        }])->findOrFail($id);

        return view('employees.show', compact('employee'));
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Employee Page
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        $departments = Department::orderBy('name')->get();
        $shifts = Shift::orderBy('name')->get();

        return view(
            'employees.edit',
            compact('employee', 'departments', 'shifts')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Employee
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id) {
        $employee = Employee::findOrFail($id);
        $user = $employee->user;

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
                'unique:users,email,' . ($user ? $user->id : 'NULL'),
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
            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],
            'department_id' => [
                'required',
                'exists:departments,id',
            ],
            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],
            'shift_id' => [
                'required',
                'exists:shifts,id',
            ],
            'joining_date' => [
                'required',
                'date',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);

        DB::transaction(function () use ($employee, $user, $validated) {
            if ($user) {
                $userData = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ];
                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }
                $user->update($userData);
            } else {
                // If user doesn't exist yet, create one
                $newUser = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'] ?? 'password'),
                    'role' => 'employee',
                ]);
                $employee->user_id = $newUser->id;
            }

            $employee->update([
                'department_id' => $validated['department_id'],
                'shift_id' => $validated['shift_id'],
                'designation' => $validated['designation'] ?? null,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'employee_code' => $validated['employee_code'],
                'joining_date' => $validated['joining_date'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Employee
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Soft deactivation or deletion as per system design
        if ($employee->user) {
            $employee->user->delete();
        }
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee record removed successfully!');
    }
}
