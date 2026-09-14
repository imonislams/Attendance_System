@extends('layouts.app')

@section('title', 'Employee Report')

@section('content')

<h1>Employee Attendance Report</h1>

<br>


<form method="GET">

    <select name="employee_id">

        <option value="">
            Select Employee
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
        Generate
    </button>

</form>


<br>


@if ($selectedEmployee)

    <h2>
        {{ $selectedEmployee->name }}
    </h2>


    <table>

        <thead>

            <tr>
                <th>Date</th>
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
                    <td colspan="6">
                        No attendance found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

@endif

@endsection