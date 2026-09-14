<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalEmployees = Employee::count();

        $totalDepartments = Department::count();

        $totalShifts = Shift::count();

        $presentToday = Attendance::whereDate(
            'attendance_date',
            $today
        )->where('status', 'present')->count();

        $lateToday = Attendance::whereDate(
            'attendance_date',
            $today
        )->where('status', 'late')->count();

        $halfDayToday = Attendance::whereDate(
            'attendance_date',
            $today
        )->where('status', 'half_day')->count();

        $totalAttendanceToday = Attendance::whereDate(
            'attendance_date',
            $today
        )->count();

        $absentToday = max(
            0,
            $totalEmployees - $totalAttendanceToday
        );

        $recentAttendances = Attendance::with([
            'employee',
            'shift'
        ])
        ->latest()
        ->take(10)
        ->get();

        return view('dashboard.index', compact(
            'totalEmployees',
            'totalDepartments',
            'totalShifts',
            'presentToday',
            'lateToday',
            'halfDayToday',
            'absentToday',
            'totalAttendanceToday',
            'recentAttendances'
        ));
    }
}