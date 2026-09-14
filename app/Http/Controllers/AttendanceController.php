<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
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
        $employees = Employee::orderBy('name')->get();
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

        $lateAfter = Carbon::createFromFormat(
            'H:i:s',
            $shift->late_after
        );

        $currentTime = Carbon::createFromFormat(
            'H:i:s',
            $checkIn->format('H:i:s')
        );

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

        $checkIn = Carbon::createFromFormat(
            'H:i:s',
            $attendance->check_in
        );

        $checkOut = Carbon::now();

        $workingMinutes = $checkIn->diffInMinutes($checkOut);

        $attendance->update([
            'check_out' => $checkOut->format('H:i:s'),
            'working_minutes' => $workingMinutes,
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

        if ($validated['check_in'] && $validated['check_out']) {
            $checkIn = Carbon::createFromFormat(
                'H:i',
                $validated['check_in']
            );

            $checkOut = Carbon::createFromFormat(
                'H:i',
                $validated['check_out']
            );

            $workingMinutes = $checkIn->diffInMinutes($checkOut);
        }

        $attendance->update([
            ...$validated,
            'working_minutes' => $workingMinutes,
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
}