@extends('layouts.app')

@section('title', 'Check In')

@section('content')

<h1>Employee Check In</h1>

<br>

@if ($errors->any())

    <div class="alert alert-danger">

        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach

    </div>

@endif


<form
    action="/attendance/check-in"
    method="POST"
>

    @csrf


    <div class="form-group">

        <label>Employee</label>

        <select name="employee_id">

            <option value="">
                Select Employee
            </option>

            @foreach ($employees as $employee)

                <option
                    value="{{ $employee->id }}"
                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                >
                    {{ $employee->employee_code }}
                    -
                    {{ $employee->name }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="form-group">

        <label>Shift</label>

        <select name="shift_id">

            <option value="">
                Select Shift
            </option>

            @foreach ($shifts as $shift)

                <option
                    value="{{ $shift->id }}"
                    {{ old('shift_id') == $shift->id ? 'selected' : '' }}
                >
                    {{ $shift->name }}
                    ({{ $shift->start_time }} - {{ $shift->end_time }})
                </option>

            @endforeach

        </select>

    </div>


    <div class="form-group">

        <label>Date</label>

        <input
            type="date"
            name="attendance_date"
            value="{{ old('attendance_date', date('Y-m-d')) }}"
        >

    </div>


    <div class="form-group">

        <label>Notes</label>

        <textarea
            name="notes"
            rows="4"
        >{{ old('notes') }}</textarea>

    </div>


    <button
        type="submit"
        class="btn btn-primary"
    >
        Check In
    </button>


    <a
        href="/attendance"
        class="btn"
    >
        Back
    </a>

</form>

@endsection