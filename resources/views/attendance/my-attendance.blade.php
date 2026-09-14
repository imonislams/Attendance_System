@extends('layouts.app')

@section('title', 'My Attendance History')

@section('content')

<div class="attendance-page">

    <div class="page-heading" style="margin-bottom: 24px;">
        <div>
            <h1>My Attendance History</h1>
            <p style="color: #64748b; font-size: 14px;">View your past attendance records and working hours.</p>
        </div>
    </div>

    <!-- FILTER FORM -->
    <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
        <form method="GET" action="{{ route('employee.my-attendance') }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label style="font-size: 12px; font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>

            <div>
                <button type="submit" style="background: #6366f1; color: white; padding: 9px 20px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;">
                    Filter
                </button>
                <a href="{{ route('employee.my-attendance') }}" style="background: #e2e8f0; color: #334155; padding: 9px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-left: 8px;">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 12px 24px;">#</th>
                        <th style="padding: 12px 24px;">Date</th>
                        <th style="padding: 12px 24px;">Shift</th>
                        <th style="padding: 12px 24px;">Check In</th>
                        <th style="padding: 12px 24px;">Check Out</th>
                        <th style="padding: 12px 24px;">Working Time</th>
                        <th style="padding: 12px 24px;">Late Min</th>
                        <th style="padding: 12px 24px;">Overtime</th>
                        <th style="padding: 12px 24px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 24px; color: #94a3b8;">
                                {{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}
                            </td>
                            <td style="padding: 14px 24px; font-weight: 600; color: #0f172a;">
                                {{ $attendance->attendance_date->format('d M, Y') }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569;">
                                {{ $attendance->shift ? $attendance->shift->name : '-' }}
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
                            <td style="padding: 14px 24px; color: #059669; font-weight: 600;">
                                {{ floor($attendance->overtime_minutes / 60) }}h {{ $attendance->overtime_minutes % 60 }}m
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
                            <td colspan="9" style="padding: 24px; text-align: center; color: #94a3b8;">
                                No attendance records found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0;">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
