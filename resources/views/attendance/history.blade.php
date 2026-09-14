@extends('layouts.app')

@section('title', 'Attendance History')

@section('content')

<h1>Attendance History</h1>

<br>


<form
    action="/attendance/history"
    method="GET"
    class="filter-form"
>

    <select name="employee_id">

        <option value="">
            All Employees
        </option>

        @foreach ($employees as $employee)

            <option
                value="{{ $employee->id }}"
                {{ request('employee_id') == $employee->id ? 'selected' : '' }}
            >
                {{ $employee->name }}
            </option>

        @endforeach

    </select>


    <input
        type="date"
        name="from_date"
        value="{{ request('from_date') }}"
    >


    <input
        type="date"
        name="to_date"
        value="{{ request('to_date') }}"
    >


    <button
        type="submit"
        class="btn btn-primary"
    >
        Search
    </button>

</form>


<br>


<table>

    <thead>

        <tr>
            <th>Date</th>
            <th>Employee</th>
            <th>Department</th>
            <th>Shift</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Status</th>
            <th>Working</th>
        </tr>

    </thead>


    <tbody>

        @forelse ($attendances as $attendance)

            <tr>

                <td>
                    {{ $attendance->attendance_date->format('Y-m-d') }}
                </td>

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

                <td>
                    {{ intdiv($attendance->working_minutes, 60) }}h
                    {{ $attendance->working_minutes % 60 }}m
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="8">
                    No attendance found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>


<br>

{{ $attendances->links() }}

@endsection