@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<h1>Dashboard</h1>

<br>


<div class="dashboard-grid">

    <div class="dashboard-card">
        <h3>Total Employees</h3>
        <strong>{{ $totalEmployees }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Departments</h3>
        <strong>{{ $totalDepartments }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Shifts</h3>
        <strong>{{ $totalShifts }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Present Today</h3>
        <strong>{{ $presentToday }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Late Today</h3>
        <strong>{{ $lateToday }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Absent Today</h3>
        <strong>{{ $absentToday }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Half Day</h3>
        <strong>{{ $halfDayToday }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Total Attendance</h3>
        <strong>{{ $totalAttendanceToday }}</strong>
    </div>

</div>


<br>

<h2>Recent Attendance</h2>


<table>

    <thead>

        <tr>
            <th>Employee</th>
            <th>Shift</th>
            <th>Date</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Status</th>
        </tr>

    </thead>


    <tbody>

        @forelse ($recentAttendances as $attendance)

            <tr>

                <td>
                    {{ $attendance->employee->name }}
                </td>

                <td>
                    {{ $attendance->shift->name }}
                </td>

                <td>
                    {{ $attendance->attendance_date->format('Y-m-d') }}
                </td>

                <td>
                    {{ $attendance->check_in ?? '-' }}
                </td>

                <td>
                    {{ $attendance->check_out ?? '-' }}
                </td>

                <td>
                    {{ ucfirst($attendance->status) }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="6">
                    No attendance found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

@endsection