@extends('layouts.app')

@section('title', 'Daily Report')

@section('content')

<h1>Daily Attendance Report</h1>

<br>


<form method="GET">

    <input
        type="date"
        name="date"
        value="{{ $date }}"
    >

    <button
        type="submit"
        class="btn btn-primary"
    >
        Generate
    </button>

</form>


<br>


<div class="dashboard-grid">

    <div class="dashboard-card">
        <h3>Total Employees</h3>
        <strong>{{ $totalEmployees }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Present</h3>
        <strong>{{ $present }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Late</h3>
        <strong>{{ $late }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Half Day</h3>
        <strong>{{ $halfDay }}</strong>
    </div>

    <div class="dashboard-card">
        <h3>Absent</h3>
        <strong>{{ $absent }}</strong>
    </div>

</div>


<br>


<table>

    <thead>

        <tr>
            <th>Employee</th>
            <th>Department</th>
            <th>Shift</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Status</th>
        </tr>

    </thead>

    <tbody>

        @forelse ($attendances as $attendance)

            <tr>

                <td>
                    {{ $attendance->employee->name }}
                </td>

                <td>
                    {{ $attendance->employee->department->name ?? '-' }}
                </td>

                <td>
                    {{ $attendance->shift->name }}
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