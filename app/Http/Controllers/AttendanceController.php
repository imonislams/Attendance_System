<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        $query = Attendance::with(['employee.department', 'shift'])
            ->latest('attendance_date')
            ->latest('check_in');

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(15)->withQueryString();

        $employees = Employee::orderBy('name')->get();

        return view('attendance.index', compact(
            'attendances',
            'employees'
        ));
    }

    public function checkIn()
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        $shifts = Shift::orderBy('start_time')->get();

        return view('attendance.check-in', compact(
            'employees',
            'shifts'
        ));
    }

    public function storeCheckIn(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'attendance_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $exists = Attendance::where('employee_id', $validated['employee_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' => 'This employee already has attendance for this date.'
                ]);
        }

        $shift = Shift::findOrFail($validated['shift_id']);
        $checkIn = Carbon::now();

        $lateAfter = Carbon::createFromFormat('H:i:s', $shift->late_after);
        $currentTime = Carbon::createFromFormat('H:i:s', $checkIn->format('H:i:s'));

        $status = 'present';
        $lateMinutes = 0;

        if ($currentTime->greaterThan($lateAfter)) {
            $status = 'late';
            $lateMinutes = $lateAfter->diffInMinutes($currentTime);
        }

        Attendance::create([
            'employee_id' => $validated['employee_id'],
            'shift_id' => $validated['shift_id'],
            'attendance_date' => $validated['attendance_date'],
            'check_in' => $checkIn->format('H:i:s'),
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'working_minutes' => 0,
            'overtime_minutes' => 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect('/attendance')
            ->with('success', 'Employee checked in successfully!');
    }

    public function checkOut($id)
    {
        $attendance = Attendance::findOrFail($id);

        if ($attendance->check_out) {
            return back()->withErrors([
                'checkout' => 'Employee already checked out.'
            ]);
        }

        if (!$attendance->check_in) {
            return back()->withErrors([
                'checkout' => 'Employee has not checked in.'
            ]);
        }

        $checkInDateTime = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $attendance->check_in);
        $checkOutDateTime = Carbon::now();

        $workingMinutes = $checkInDateTime->diffInMinutes($checkOutDateTime);

        $overtimeMinutes = 0;
        if ($attendance->shift) {
            $shift = $attendance->shift;
            $shiftStart = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $shift->start_time);
            $shiftEnd = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $shift->end_time);

            if ($shiftEnd->lessThan($shiftStart)) {
                $shiftEnd->addDay();
            }

            if ($checkOutDateTime->greaterThan($shiftEnd)) {
                $overtimeMinutes = $shiftEnd->diffInMinutes($checkOutDateTime);
            }
        }

        $attendance->update([
            'check_out' => $checkOutDateTime->format('H:i:s'),
            'working_minutes' => $workingMinutes,
            'overtime_minutes' => $overtimeMinutes,
        ]);

        return redirect('/attendance')
            ->with('success', 'Employee checked out successfully!');
    }

    public function history(Request $request)
    {
        $query = Attendance::with([
            'employee.department',
            'shift'
        ])->latest('attendance_date');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendances = $query->paginate(15)->withQueryString();

        $employees = Employee::orderBy('name')->get();

        return view('attendance.history', compact(
            'attendances',
            'employees'
        ));
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $employees = Employee::orderBy('name')->get();
        $shifts = Shift::orderBy('start_time')->get();

        return view('attendance.edit', compact(
            'attendance',
            'employees',
            'shifts'
        ));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,late,absent,half_day',
            'notes' => 'nullable|string',
        ]);

        $workingMinutes = 0;
        $overtimeMinutes = 0;

        if ($validated['check_in'] && $validated['check_out']) {
            $checkIn = Carbon::parse($validated['attendance_date'] . ' ' . $validated['check_in']);
            $checkOut = Carbon::parse($validated['attendance_date'] . ' ' . $validated['check_out']);

            if ($checkOut->lessThan($checkIn)) {
                $checkOut->addDay();
            }

            $workingMinutes = $checkIn->diffInMinutes($checkOut);

            $shift = Shift::find($validated['shift_id']);
            if ($shift) {
                $shiftStart = Carbon::parse($validated['attendance_date'] . ' ' . $shift->start_time);
                $shiftEnd = Carbon::parse($validated['attendance_date'] . ' ' . $shift->end_time);

                if ($shiftEnd->lessThan($shiftStart)) {
                    $shiftEnd->addDay();
                }

                if ($checkOut->greaterThan($shiftEnd)) {
                    $overtimeMinutes = $shiftEnd->diffInMinutes($checkOut);
                }
            }
        }

        $attendance->update([
            'employee_id' => $validated['employee_id'],
            'shift_id' => $validated['shift_id'],
            'attendance_date' => $validated['attendance_date'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'status' => $validated['status'],
            'working_minutes' => $workingMinutes,
            'overtime_minutes' => $overtimeMinutes,
            'notes' => $validated['notes'],
        ]);

        return redirect('/attendance')
            ->with('success', 'Attendance updated successfully!');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect('/attendance')
            ->with('success', 'Attendance deleted successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Employee Self Check-In / Check-Out
    |--------------------------------------------------------------------------
    */

    public function employeeCheckIn(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->employee) {
            return back()->with('error', 'Employee record not found.');
        }

        try {
            $this->attendanceService->checkIn($user->employee, $request->input('notes'));
            return back()->with('success', 'Check-In successful!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function employeeCheckOut(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->employee) {
            return back()->with('error', 'Employee record not found.');
        }

        try {
            $this->attendanceService->checkOut($user->employee);
            return back()->with('success', 'Check-Out successful!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function myAttendance(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'No employee record linked.');
        }

        $query = Attendance::where('employee_id', $employee->id)
            ->with('shift')
            ->latest('attendance_date');

        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendances = $query->paginate(15)->withQueryString();

        return view('attendance.my-attendance', compact('attendances', 'employee'));
    }
}
