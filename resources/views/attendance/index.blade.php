@extends('layouts.app')

@section('title', 'Attendance')

@section('content')

<h1>Attendance</h1>

<br>

@if (session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


@if ($errors->any())

    <div class="alert alert-danger">

        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach

    </div>

@endif


<a
    href="/attendance/check-in"
    class="btn btn-primary"
>
    + Check In
</a>

<a
    href="/attendance/history"
    class="btn"
>
    Attendance History
</a>

<br><br>


<form
    action="/attendance"
    method="GET"
    class="filter-form"
>

    <input
        type="date"
        name="date"
        value="{{ request('date') }}"
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


    <select name="status">

        <option value="">
            All Status
        </option>

        <option
            value="present"
            {{ request('status') == 'present' ? 'selected' : '' }}
        >
            Present
        </option>

        <option
            value="late"
            {{ request('status') == 'late' ? 'selected' : '' }}
        >
            Late
        </option>

        <option
            value="half_day"
            {{ request('status') == 'half_day' ? 'selected' : '' }}
        >
            Half Day
        </option>

        <option
            value="absent"
            {{ request('status') == 'absent' ? 'selected' : '' }}
        >
            Absent
        </option>

    </select>


    <button
        type="submit"
        class="btn btn-primary"
    >
        Filter
    </button>

</form>


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
            <th>Late</th>
            <th>Working</th>
            <th>Action</th>
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
                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                </td>

                <td>
                    {{ $attendance->late_minutes }} min
                </td>

                <td>
                    {{ intdiv($attendance->working_minutes, 60) }}h
                    {{ $attendance->working_minutes % 60 }}m
                </td>

                <td>

                    @if (!$attendance->check_out)

                        <form
                            action="/attendance/{{ $attendance->id }}/check-out"
                            method="POST"
                            style="display:inline"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Check Out
                            </button>

                        </form>

                    @endif


                    <a
                        href="/attendance/{{ $attendance->id }}/edit"
                        class="btn btn-warning"
                    >
                        Edit
                    </a>


                    <form
                        action="/attendance/{{ $attendance->id }}"
                        method="POST"
                        style="display:inline"
                        onsubmit="return confirm('Delete this attendance?')"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger"
                        >
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="10">
                    No attendance found.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>


<br>

{{ $attendances->links() }}

@endsection