@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')

<div class="dashboard-page">

    <!-- WELCOME BANNER -->
    <div style="background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <span style="font-size: 13px; font-weight: 600; color: #6366f1; text-transform: uppercase; letter-spacing: 0.5px;">Welcome Back</span>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 4px 0;">{{ $employee->name }}</h1>
            <p style="color: #64748b; margin: 0; font-size: 14px;">
                Employee ID: <strong style="color: #0f172a;">{{ $employee->employee_code }}</strong> |
                Department: <strong style="color: #0f172a;">{{ $employee->department ? $employee->department->name : 'N/A' }}</strong> |
                Shift: <strong style="color: #0f172a;">{{ $employee->shift ? $employee->shift->name . ' (' . substr($employee->shift->start_time, 0, 5) . ' - ' . substr($employee->shift->end_time, 0, 5) . ')' : 'Not Assigned' }}</strong>
            </p>
        </div>

        <div style="text-align: right;">
            <div style="font-size: 13px; color: #64748b;">Today's Date</div>
            <div style="font-size: 18px; font-weight: 700; color: #0f172a;">{{ now()->format('l, F j, Y') }}</div>
            <div style="font-size: 14px; font-weight: 600; color: #6366f1;">Server Time: {{ now()->format('h:i A') }}</div>
        </div>
    </div>

    <!-- TODAY ATTENDANCE ACTION CARD -->
    <div style="background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <span style="color: #6366f1;">⏱</span> Today's Attendance Punch
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; align-items: center;">
            <!-- Status Pill -->
            <div style="padding: 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;">Status</span>
                @if(!$todayAttendance)
                    <span style="display: inline-block; background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 20px; font-size: 13px; font-weight: 700;">Not Checked In</span>
                @elseif($todayAttendance->check_in && !$todayAttendance->check_out)
                    <span style="display: inline-block; background: #dbeafe; color: #2563eb; padding: 4px 10px; border-radius: 20px; font-size: 13px; font-weight: 700;">Checked In</span>
                @else
                    <span style="display: inline-block; background: #d1fae5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 13px; font-weight: 700;">Shift Completed</span>
                @endif
            </div>

            <!-- Check In Time -->
            <div style="padding: 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;">Check-In Time</span>
                <strong style="font-size: 16px; color: #0f172a;">
                    {{ $todayAttendance && $todayAttendance->check_in ? date('h:i:s A', strtotime($todayAttendance->check_in)) : '--:--' }}
                </strong>
            </div>

            <!-- Check Out Time -->
            <div style="padding: 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;">Check-Out Time</span>
                <strong style="font-size: 16px; color: #0f172a;">
                    {{ $todayAttendance && $todayAttendance->check_out ? date('h:i:s A', strtotime($todayAttendance->check_out)) : '--:--' }}
                </strong>
            </div>

            <!-- Working Hours -->
            <div style="padding: 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;">Working Hours</span>
                <strong style="font-size: 16px; color: #0f172a;">
                    @if($todayAttendance && $todayAttendance->working_minutes > 0)
                        {{ floor($todayAttendance->working_minutes / 60) }}h {{ $todayAttendance->working_minutes % 60 }}m
                    @else
                        0h 0m
                    @endif
                </strong>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 20px; display: flex; gap: 12px; flex-wrap: wrap;">
            @if(!$todayAttendance)
                <form action="{{ route('employee.check-in') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: #10b981; color: #ffffff; font-weight: 700; padding: 12px 28px; border-radius: 8px; border: none; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                        <span>➔</span> Check In Now
                    </button>
                </form>
            @elseif(!$todayAttendance->check_out)
                <form action="{{ route('employee.check-out') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: #ef4444; color: #ffffff; font-weight: 700; padding: 12px 28px; border-radius: 8px; border: none; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                        <span>⏹</span> Check Out Now
                    </button>
                </form>
            @else
                <button disabled style="background: #94a3b8; color: #ffffff; font-weight: 700; padding: 12px 28px; border-radius: 8px; border: none; cursor: not-allowed; font-size: 15px;">
                    ✓ Attendance Completed Today
                </button>
            @endif
        </div>
    </div>

    <!-- MONTHLY SUMMARY STATS -->
    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">This Month's Summary ({{ now()->format('F Y') }})</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <div style="font-size: 13px; color: #64748b; font-weight: 600;">Days Present</div>
            <div style="font-size: 28px; font-weight: 800; color: #10b981; margin-top: 4px;">{{ $totalPresent }}</div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <div style="font-size: 13px; color: #64748b; font-weight: 600;">Late Days</div>
            <div style="font-size: 28px; font-weight: 800; color: #f59e0b; margin-top: 4px;">{{ $totalLate }}</div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <div style="font-size: 13px; color: #64748b; font-weight: 600;">Total Working Hours</div>
            <div style="font-size: 28px; font-weight: 800; color: #6366f1; margin-top: 4px;">
                {{ floor($totalWorkingMinutes / 60) }}h {{ $totalWorkingMinutes % 60 }}m
            </div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <div style="font-size: 13px; color: #64748b; font-weight: 600;">Overtime Hours</div>
            <div style="font-size: 28px; font-weight: 800; color: #06b6d4; margin-top: 4px;">
                {{ floor($totalOvertimeMinutes / 60) }}h {{ $totalOvertimeMinutes % 60 }}m
            </div>
        </div>
    </div>

    <!-- RECENT ATTENDANCE HISTORY -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Recent Attendance History</h3>
            <a href="{{ route('employee.my-attendance') }}" style="font-size: 13px; color: #6366f1; font-weight: 600; text-decoration: none;">View All →</a>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 12px 24px;">Date</th>
                        <th style="padding: 12px 24px;">Check In</th>
                        <th style="padding: 12px 24px;">Check Out</th>
                        <th style="padding: 12px 24px;">Working Time</th>
                        <th style="padding: 12px 24px;">Late Min</th>
                        <th style="padding: 12px 24px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttendances as $attendance)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 24px; font-weight: 600; color: #0f172a;">
                                {{ $attendance->attendance_date->format('d M, Y') }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569;">
                                {{ $attendance->check_in ? date('h:i A', strtotime($attendance->check_in)) : '-' }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569;">
                                {{ $attendance->check_out ? date('h:i A', strtotime($attendance->check_out)) : '-' }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569;">
                                {{ floor($attendance->working_minutes / 60) }}h {{ $attendance->working_minutes % 60 }}m
                            </td>
                            <td style="padding: 14px 24px; color: {{ $attendance->late_minutes > 0 ? '#ef4444' : '#64748b' }};">
                                {{ $attendance->late_minutes }}m
                            </td>
                            <td style="padding: 14px 24px;">
                                @if($attendance->status === 'present')
                                    <span style="background: #d1fae5; color: #059669; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Present</span>
                                @elseif($attendance->status === 'late')
                                    <span style="background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Late</span>
                                @else
                                    <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">{{ ucfirst($attendance->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 24px; text-align: center; color: #94a3b8;">
                                No attendance records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
