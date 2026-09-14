@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')

<div class="employee-form-page">


    <!-- HEADER -->
    <div class="employee-page-header">

        <div>

            <div class="employee-page-label">
                EMPLOYEE MANAGEMENT
            </div>

            <h1 class="employee-page-title">
                Edit Employee
            </h1>

            <p class="employee-page-subtitle">
                Update employee information.
            </p>

        </div>


        <a
            href="{{ url('/employees') }}"
            class="employee-back-btn"
        >
            ← Back to Employees
        </a>

    </div>


    <!-- VALIDATION ERROR -->
    @if($errors->any())

        <div class="employee-error">

            <strong>
                Please fix the following errors:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- FORM CARD -->
    <div class="employee-form-card">


        <!-- FORM HEADER -->
        <div class="employee-form-header">

            <div class="form-header-avatar">

                {{ strtoupper(
                    substr($employee->name, 0, 1)
                ) }}

            </div>


            <div>

                <h2>
                    {{ $employee->name }}
                </h2>

                <p>
                    Employee Code:
                    {{ $employee->employee_code }}
                </p>

            </div>

        </div>


        <!-- FORM -->
        <form
            action="{{ url('/employees/' . $employee->id) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            <div class="employee-form-grid">


                <!-- NAME -->
                <div class="employee-form-group">

                    <label>
                        Full Name
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $employee->name) }}"
                        required
                    >

                    @error('name')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- EMAIL -->
                <div class="employee-form-group">

                    <label>
                        Email Address
                        <span>*</span>
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $employee->email) }}"
                        required
                    >

                    @error('email')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- PHONE -->
                <div class="employee-form-group">

                    <label>
                        Phone Number
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $employee->phone) }}"
                        required
                    >

                    @error('phone')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- EMPLOYEE CODE -->
                <div class="employee-form-group">

                    <label>
                        Employee Code
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="employee_code"
                        value="{{ old('employee_code', $employee->employee_code) }}"
                        required
                    >

                    @error('employee_code')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- DEPARTMENT -->
                <div class="employee-form-group">

                    <label>
                        Department
                        <span>*</span>
                    </label>

                    <select
                        name="department_id"
                        required
                    >

                        <option value="">
                            Select Department
                        </option>

                        @foreach($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                {{ old(
                                    'department_id',
                                    $employee->department_id
                                ) == $department->id
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                {{ $department->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('department_id')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- JOINING DATE -->
                <div class="employee-form-group">

                    <label>
                        Joining Date
                        <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="joining_date"
                        value="{{ old(
                            'joining_date',
                            $employee->joining_date
                                ? $employee->joining_date->format('Y-m-d')
                                : ''
                        ) }}"
                        required
                    >

                    @error('joining_date')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


            </div>


            <!-- BUTTONS -->
            <div class="employee-form-actions">

                <a
                    href="{{ url('/employees') }}"
                    class="employee-cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="employee-save-btn"
                >
                    ✓ Update Employee
                </button>

            </div>


        </form>

    </div>

</div>

@endsection