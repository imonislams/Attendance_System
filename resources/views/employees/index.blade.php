@extends('layouts.app')

@section('title', 'Employees')

@section('content')

<div class="employee-page">

    <!-- PAGE HEADER -->
    <div class="employee-page-header">

        <div>
            <div class="employee-page-label">
                EMPLOYEE MANAGEMENT
            </div>

            <h1 class="employee-page-title">
                Employees
            </h1>

            <p class="employee-page-subtitle">
                Manage employees, departments and employee information.
            </p>
        </div>

        <a
            href="{{ url('/employees/create') }}"
            class="employee-add-btn"
        >
            <span>+</span>
            Add Employee
        </a>

    </div>


    <!-- SUCCESS MESSAGE -->
    @if(session('success'))

        <div class="employee-success">

            <div class="employee-success-left">

                <div class="success-circle">
                    ✓
                </div>

                <span>
                    {{ session('success') }}
                </span>

            </div>

            <button
                type="button"
                onclick="this.parentElement.remove()"
                class="success-close"
            >
                ×
            </button>

        </div>

    @endif


    <!-- ERROR MESSAGE -->
    @if($errors->any())

        <div class="employee-error">

            <strong>
                Something went wrong
            </strong>

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    <!-- MAIN CARD -->
    <div class="employee-card">


        <!-- CARD HEADER -->
        <div class="employee-card-header">

            <div>

                <h2>
                    Employee Directory
                </h2>

                <p>
                    Total
                    <strong>{{ $employees->count() }}</strong>
                    employees
                </p>

            </div>


            <!-- SEARCH -->
            <div class="employee-search">

                <span class="search-icon">
                    🔍
                </span>

                <input
                    type="text"
                    id="employeeSearch"
                    placeholder="Search employee..."
                    autocomplete="off"
                >

            </div>

        </div>


        <!-- TABLE -->
        <div class="employee-table-wrapper">

            <table
                class="employee-table"
                id="employeeTable"
            >

                <thead>

                    <tr>

                        <th class="number-column">
                            #
                        </th>

                        <th>
                            EMPLOYEE
                        </th>

                        <th>
                            CODE
                        </th>

                        <th>
                            DEPARTMENT
                        </th>

                        <th>
                            EMAIL
                        </th>

                        <th>
                            PHONE
                        </th>

                        <th>
                            JOINING DATE
                        </th>

                        <th class="action-column">
                            ACTION
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($employees as $employee)

                        <tr class="employee-row">


                            <!-- NUMBER -->
                            <td class="number-column">

                                <span class="row-number">
                                    {{ $loop->iteration }}
                                </span>

                            </td>


                            <!-- EMPLOYEE -->
                            <td>

                                <div class="employee-profile">

                                    <div class="employee-avatar">

                                        {{ strtoupper(
                                            substr($employee->name, 0, 1)
                                        ) }}

                                    </div>


                                    <div class="employee-name-box">

                                        <div class="employee-name">
                                            {{ $employee->name }}
                                        </div>

                                        <div class="employee-role">
                                            Employee
                                        </div>

                                    </div>

                                </div>

                            </td>


                            <!-- CODE -->
                            <td>

                                <span class="employee-code">
                                    {{ $employee->employee_code }}
                                </span>

                            </td>


                            <!-- DEPARTMENT -->
                            <td>

                                @if($employee->department)

                                    <span class="department-tag">
                                        {{ $employee->department->name }}
                                    </span>

                                @else

                                    <span class="no-department">
                                        No Department
                                    </span>

                                @endif

                            </td>


                            <!-- EMAIL -->
                            <td>

                                <span class="employee-email">
                                    {{ $employee->email }}
                                </span>

                            </td>


                            <!-- PHONE -->
                            <td>

                                <span class="employee-phone">
                                    {{ $employee->phone }}
                                </span>

                            </td>


                            <!-- JOINING DATE -->
                            <td>

                                @if($employee->joining_date)

                                    <span class="joining-date">

                                        {{ $employee->joining_date->format('d M, Y') }}

                                    </span>

                                @else

                                    <span class="no-data">
                                        -
                                    </span>

                                @endif

                            </td>


                            <!-- ACTION -->
                            <td class="action-cell">

                                <div class="employee-actions">


                                    <!-- EDIT -->
                                    <a
                                        href="{{ url('/employees/' . $employee->id . '/edit') }}"
                                        class="employee-edit-btn"
                                    >
                                        <span>✎</span>
                                        Edit
                                    </a>


                                    <!-- DELETE -->
                                    <form
                                        action="{{ url('/employees/' . $employee->id) }}"
                                        method="POST"
                                        class="employee-delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete {{ $employee->name }}?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="employee-delete-btn"
                                        >
                                            <span>🗑</span>
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>


                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="employee-empty"
                            >

                                <div class="empty-employee-icon">
                                    👥
                                </div>

                                <h3>
                                    No Employees Found
                                </h3>

                                <p>
                                    There are no employees in the system yet.
                                </p>

                                <a
                                    href="{{ url('/employees/create') }}"
                                    class="employee-add-btn empty-add-btn"
                                >
                                    + Add First Employee
                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- TABLE FOOTER -->
        @if($employees->count() > 0)

            <div class="employee-card-footer">

                <span>
                    Showing
                    <strong>{{ $employees->count() }}</strong>
                    employee(s)
                </span>

                <span>
                    Employee Management
                </span>

            </div>

        @endif

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('employeeSearch');

    const table =
        document.getElementById('employeeTable');

    if (!searchInput || !table) {
        return;
    }


    searchInput.addEventListener('keyup', function () {

        const searchValue =
            this.value.toLowerCase().trim();

        const rows =
            table.querySelectorAll(
                'tbody .employee-row'
            );


        rows.forEach(function (row) {

            const rowText =
                row.innerText.toLowerCase();

            if (rowText.includes(searchValue)) {

                row.style.display = '';

            } else {

                row.style.display = 'none';

            }

        });

    });

});

</script>

@endsection