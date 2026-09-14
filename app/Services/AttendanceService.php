<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Exception;

class AttendanceService
{
    /**
     * Process Employee Check In
     */
    public function checkIn(Employee $employee, ?string $notes = null): Attendance
    {
        if ($employee->status !== 'active') {
            throw new Exception('Inactive employee cannot check in.');
        }

        if (!$employee->shift_id || !$employee->shift) {
            throw new Exception('Employee does not have an assigned shift.');
        }

        $now = Carbon::now();
        $todayDate = $now->format('Y-m-d');

        // Check for existing attendance today
        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $todayDate)
            ->first();

        if ($existing) {
            throw new Exception('You have already checked in for today.');
        }

        $shift = $employee->shift;
        $checkInTimeStr = $now->format('H:i:s');

        // Calculate late minutes
        $lateAfter = Carbon::createFromFormat('H:i:s', $shift->late_after);
        $currentTime = Carbon::createFromFormat('H:i:s', $checkInTimeStr);

        $status = 'present';
        $lateMinutes = 0;

        if ($currentTime->greaterThan($lateAfter)) {
            $status = 'late';
            $lateMinutes = $lateAfter->diffInMinutes($currentTime);
        }

        return Attendance::create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'attendance_date' => $todayDate,
            'check_in' => $checkInTimeStr,
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'working_minutes' => 0,
            'overtime_minutes' => 0,
            'notes' => $notes,
        ]);
    }

    /**
     * Process Employee Check Out
     */
    public function checkOut(Employee $employee): Attendance
    {
        if ($employee->status !== 'active') {
            throw new Exception('Inactive employee cannot check out.');
        }

        $now = Carbon::now();
        $todayDate = $now->format('Y-m-d');

        // Find today's check-in or latest open check-in
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereNull('check_out')
            ->latest('attendance_date')
            ->first();

        if (!$attendance) {
            // Check if checked out already today
            $alreadyCheckedOut = Attendance::where('employee_id', $employee->id)
                ->whereDate('attendance_date', $todayDate)
                ->whereNotNull('check_out')
                ->exists();

            if ($alreadyCheckedOut) {
                throw new Exception('You have already completed check-out for today.');
            }

            throw new Exception('No active check-in found to check out.');
        }

        $checkOutTimeStr = $now->format('H:i:s');

        // Construct full Carbon datetimes for Check-In and Check-Out to handle overnight shifts
        $checkInDateTime = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $attendance->check_in);
        $checkOutDateTime = $now;

        if ($checkOutDateTime->lessThan($checkInDateTime)) {
            $checkOutDateTime = $checkInDateTime->copy()->addMinutes(1);
        }

        $workingMinutes = $checkInDateTime->diffInMinutes($checkOutDateTime);

        // Overtime Calculation based on Shift
        $overtimeMinutes = 0;
        if ($attendance->shift) {
            $shift = $attendance->shift;
            $shiftStart = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $shift->start_time);
            $shiftEnd = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $shift->end_time);

            // Handle overnight shift end time
            if ($shiftEnd->lessThan($shiftStart)) {
                $shiftEnd->addDay();
            }

            if ($checkOutDateTime->greaterThan($shiftEnd)) {
                $overtimeMinutes = $shiftEnd->diffInMinutes($checkOutDateTime);
            }
        }

        $attendance->update([
            'check_out' => $checkOutTimeStr,
            'working_minutes' => $workingMinutes,
            'overtime_minutes' => $overtimeMinutes,
        ]);

        return $attendance;
    }
}
