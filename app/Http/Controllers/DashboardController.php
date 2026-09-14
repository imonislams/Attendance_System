<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'employee') {
            return $this->employeeDashboard($user);
        }

        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        $today = Carbon::today();

        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalShifts = Shift::count();

        $presentToday = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'present')
            ->count();

        $lateToday = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'late')
            ->count();

        $halfDayToday = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'half_day')
            ->count();

        $totalAttendanceToday = Attendance::whereDate('attendance_date', $today)->count();

        $absentToday = max(0, $totalEmployees - $totalAttendanceToday);

        $recentAttendances = Attendance::with(['employee', 'shift'])
            ->latest()
            ->take(10)
            ->get();

        $pendingLeaves = Leave::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
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
            'recentAttendances',
            'pendingLeaves'
        ));
    }

    private function employeeDashboard($user)
    {
        $employee = $user->employee;

        if (!$employee) {
            return view('dashboard.no-employee');
        }

        $today = Carbon::today();
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        // Monthly Summary
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlyAttendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->get();

        $totalPresent = $monthlyAttendances->where('status', 'present')->count();
        $totalLate = $monthlyAttendances->where('status', 'late')->count();
        $totalHalfDay = $monthlyAttendances->where('status', 'half_day')->count();
        $totalWorkingMinutes = $monthlyAttendances->sum('working_minutes');
        $totalOvertimeMinutes = $monthlyAttendances->sum('overtime_minutes');

        $recentAttendances = Attendance::where('employee_id', $employee->id)
            ->with('shift')
            ->latest('attendance_date')
            ->take(10)
            ->get();

        return view('dashboard.employee', compact(
            'employee',
            'todayAttendance',
            'totalPresent',
            'totalLate',
            'totalHalfDay',
            'totalWorkingMinutes',
            'totalOvertimeMinutes',
            'recentAttendances'
        ));
    }
}
