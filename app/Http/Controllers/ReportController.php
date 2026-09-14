<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');

        $attendances = Attendance::with([
            'employee.department',
            'shift'
        ])
        ->whereDate('attendance_date', $date)
        ->get();

        $present = $attendances
            ->where('status', 'present')
            ->count();

        $late = $attendances
            ->where('status', 'late')
            ->count();

        $halfDay = $attendances
            ->where('status', 'half_day')
            ->count();

        $totalEmployees = Employee::count();

        $absent = max(
            0,
            $totalEmployees - $attendances->count()
        );

        return view('reports.daily', compact(
            'date',
            'attendances',
            'present',
            'late',
            'halfDay',
            'absent',
            'totalEmployees'
        ));
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? Carbon::now()->format('Y-m');

        $attendances = Attendance::with([
            'employee',
            'shift'
        ])
        ->where('attendance_date', 'like', $month . '%')
        ->get();

        $present = $attendances
            ->where('status', 'present')
            ->count();

        $late = $attendances
            ->where('status', 'late')
            ->count();

        $halfDay = $attendances
            ->where('status', 'half_day')
            ->count();

        $absent = $attendances
            ->where('status', 'absent')
            ->count();

        return view('reports.monthly', compact(
            'month',
            'attendances',
            'present',
            'late',
            'halfDay',
            'absent'
        ));
    }

    public function employee(Request $request)
    {
        $employees = Employee::orderBy('name')->get();

        $attendances = collect();

        $selectedEmployee = null;

        if ($request->filled('employee_id')) {

            $selectedEmployee = Employee::findOrFail(
                $request->employee_id
            );

            $query = Attendance::with('shift')
                ->where('employee_id', $request->employee_id);

            if ($request->filled('from_date')) {
                $query->whereDate(
                    'attendance_date',
                    '>=',
                    $request->from_date
                );
            }

            if ($request->filled('to_date')) {
                $query->whereDate(
                    'attendance_date',
                    '<=',
                    $request->to_date
                );
            }

            $attendances = $query
                ->latest('attendance_date')
                ->get();
        }

        return view('reports.employee', compact(
            'employees',
            'attendances',
            'selectedEmployee'
        ));
    }
}