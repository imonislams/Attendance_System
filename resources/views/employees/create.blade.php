@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')

<div class="employee-form-page">


    <!-- HEADER -->
    <div class="employee-page-header">

        <div>

            <div class="employee-page-label">
                EMPLOYEE MANAGEMENT
            </div>

            <h1 class="employee-page-title">
                Add Employee Account
            </h1>

            <p class="employee-page-subtitle">
                Create a new employee account with login credentials and shift assignment.
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

            <div class="form-header-icon">
                +
            </div>

            <div>

                <h2>
                    Employee Account Information
                </h2>

                <p>
                    Admin-assigned Employee ID and password will be used by the employee to log in.
                </p>

            </div>

        </div>


        <!-- FORM -->
        <form
            action="{{ url('/employees') }}"
            method="POST"
        >

            @csrf


            <div class="employee-form-grid">


                <!-- EMPLOYEE ID / CODE -->
                <div class="employee-form-group">

                    <label>
                        Employee ID
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="employee_code"
                        value="{{ old('employee_code') }}"
                        placeholder="e.g. EMP001"
                        required
                    >

                    @error('employee_code')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- FULL NAME -->
                <div class="employee-form-group">

                    <label>
                        Full Name
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter full name"
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
                        value="{{ old('email') }}"
                        placeholder="employee@example.com"
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
                        value="{{ old('phone') }}"
                        placeholder="017XXXXXXXX"
                        required
                    >

                    @error('phone')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- PASSWORD -->
                <div class="employee-form-group">

                    <label>
                        Password
                        <span>*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Assign password"
                        required
                    >

                    @error('password')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- CONFIRM PASSWORD -->
                <div class="employee-form-group">

                    <label>
                        Confirm Password
                        <span>*</span>
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                        required
                    >

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
                                {{ old('department_id') == $department->id ? 'selected' : '' }}
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


                <!-- DESIGNATION -->
                <div class="employee-form-group">

                    <label>
                        Designation
                    </label>

                    <input
                        type="text"
                        name="designation"
                        value="{{ old('designation') }}"
                        placeholder="e.g. Software Engineer"
                    >

                    @error('designation')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- ASSIGNED SHIFT -->
                <div class="employee-form-group">

                    <label>
                        Assigned Shift
                        <span>*</span>
                    </label>

                    <select
                        name="shift_id"
                        required
                    >

                        <option value="">
                            Select Shift
                        </option>

                        @foreach($shifts as $shift)

                            <option
                                value="{{ $shift->id }}"
                                {{ old('shift_id') == $shift->id ? 'selected' : '' }}
                            >
                                {{ $shift->name }} ({{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }})
                            </option>

                        @endforeach

                    </select>

                    @error('shift_id')

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
                        value="{{ old('joining_date', date('Y-m-d')) }}"
                        required
                    >

                    @error('joining_date')

                        <small class="employee-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <!-- STATUS -->
                <div class="employee-form-group">

                    <label>
                        Account Status
                        <span>*</span>
                    </label>

                    <select
                        name="status"
                        required
                    >

                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive (Cannot Login)
                        </option>

                    </select>

                    @error('status')

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
                    ✓ Create Employee Account
                </button>

            </div>


        </form>

    </div>

</div>

@endsection
