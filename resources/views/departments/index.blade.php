@extends('layouts.app')

@section('title', 'Employees')

@section('content')

<div class="employee-page">

    <!-- HEADER -->

    <div class="employee-page-header">

        <div>

            <div class="employee-page-label">
                EMPLOYEE MANAGEMENT
            </div>

            <h1 class="employee-page-title">
                Employees
            </h1>

            <p class="employee-page-subtitle">
                Manage employees and employee information.
            </p>

        </div>


        <a
            href="{{ route('employees.create') }}"
            class="employee-add-btn"
        >
            <span>+</span>
            Add Employee
        </a>

    </div>


    <!-- SUCCESS -->

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

        </div>

    @endif


    <!-- CARD -->

    <div class="employee-card">


        <!-- CARD HEADER -->

        <div class="employee-card-header">

            <div>

                <h2>
                    Employee Directory
                </h2>

                <p>
                    Total
                    <strong>
                        {{ $employees->count() }}
                    </strong>
                    employees
                </p>

            </div>


            <div class="employee-search">

                <span class="search-icon">
                    🔍
                </span>

                <input
                    type="text"
                    id="employeeSearch"
                    placeholder="Search employee..."
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

                            <td class="number-column">

                                {{ $loop->iteration }}

                            </td>


                            <td>

                                <div class="employee-profile">

                                    <div class="employee-avatar">

                                        {{ strtoupper(
                                            substr($employee->name, 0, 1)
                                        ) }}

                                    </div>


                                    <div>

                                        <div class="employee-name">
                                            {{ $employee->name }}
                                        </div>

                                        <div class="employee-role">
                                            Employee
                                        </div>

                                    </div>

                                </div>

                            </td>


                            <td>

                                <span class="employee-code">
                                    {{ $employee->employee_code }}
                                </span>

                            </td>


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


                            <td>

                                {{ $employee->email }}

                            </td>


                            <td>

                                {{ $employee->phone }}

                            </td>


                            <td>

                                @if($employee->joining_date)

                                    {{ $employee->joining_date->format('d M, Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            <!-- ACTION -->

                            <td class="action-cell">

                                <div class="employee-actions">


                                    <a
                                        href="{{ route(
                                            'employees.edit',
                                            $employee->id
                                        ) }}"
                                        class="employee-edit-btn"
                                    >
                                        ✎ Edit
                                    </a>


                                    <form
                                        action="{{ route(
                                            'employees.destroy',
                                            $employee->id
                                        ) }}"
                                        method="POST"
                                        class="employee-delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete this employee?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="employee-delete-btn"
                                        >
                                            🗑 Delete
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

                                <div>
                                    👥
                                </div>

                                <h3>
                                    No Employees Found
                                </h3>

                                <p>
                                    Add your first employee.
                                </p>

                                <a
                                    href="{{ route('employees.create') }}"
                                    class="employee-add-btn"
                                >
                                    + Add Employee
                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- FOOTER -->

        <div class="employee-card-footer">

            <span>
                Showing
                <strong>
                    {{ $employees->count() }}
                </strong>
                employee(s)
            </span>

            <span>
                Employee Management
            </span>

        </div>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const search =
            document.getElementById(
                'employeeSearch'
            );

        const rows =
            document.querySelectorAll(
                '.employee-row'
            );


        if (!search) {
            return;
        }


        search.addEventListener(
            'keyup',
            function () {

                const value =
                    this.value
                        .toLowerCase()
                        .trim();


                rows.forEach(
                    function (row) {

                        const text =
                            row.innerText
                                .toLowerCase();


                        row.style.display =
                            text.includes(value)
                                ? ''
                                : 'none';

                    }
                );

            }
        );

    }
);

</script>

@endsection