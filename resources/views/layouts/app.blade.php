<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Attendance Pro')
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

</head>

<body>

<div class="app-container">

    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">
                AP
            </div>

            <div>
                <h2>Attendance</h2>
                <span>PRO SYSTEM</span>
            </div>

        </div>


        <div class="sidebar-menu">

            <p class="menu-title">
                MAIN MENU
            </p>

            <a
                href="/dashboard"
                class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}"
            >
                <span class="menu-icon">▦</span>
                <span>Dashboard</span>
            </a>


            <a
                href="/employees"
                class="menu-item {{ request()->is('employees*') ? 'active' : '' }}"
            >
                <span class="menu-icon">👥</span>
                <span>Employees</span>
            </a>


            <a
                href="/departments"
                class="menu-item {{ request()->is('departments*') ? 'active' : '' }}"
            >
                <span class="menu-icon">▣</span>
                <span>Departments</span>
            </a>


            <a
                href="/shifts"
                class="menu-item {{ request()->is('shifts*') ? 'active' : '' }}"
            >
                <span class="menu-icon">◷</span>
                <span>Shifts</span>
            </a>


            <p class="menu-title">
                ATTENDANCE
            </p>


            <a
                href="/attendance"
                class="menu-item {{ request()->is('attendance') ? 'active' : '' }}"
            >
                <span class="menu-icon">✓</span>
                <span>Attendance</span>
            </a>


            <a
                href="/attendance/check-in"
                class="menu-item {{ request()->is('attendance/check-in') ? 'active' : '' }}"
            >
                <span class="menu-icon">→</span>
                <span>Check In</span>
            </a>


            <a
                href="/attendance/history"
                class="menu-item {{ request()->is('attendance/history') ? 'active' : '' }}"
            >
                <span class="menu-icon">◴</span>
                <span>Attendance History</span>
            </a>


            <p class="menu-title">
                REPORTS
            </p>


            <a
                href="/reports/daily"
                class="menu-item {{ request()->is('reports/daily') ? 'active' : '' }}"
            >
                <span class="menu-icon">▤</span>
                <span>Daily Report</span>
            </a>


            <a
                href="/reports/monthly"
                class="menu-item {{ request()->is('reports/monthly') ? 'active' : '' }}"
            >
                <span class="menu-icon">▥</span>
                <span>Monthly Report</span>
            </a>


            <a
                href="/reports/employee"
                class="menu-item {{ request()->is('reports/employee') ? 'active' : '' }}"
            >
                <span class="menu-icon">▤</span>
                <span>Employee Report</span>
            </a>

        </div>


        <!-- SIDEBAR FOOTER -->

        <div class="sidebar-footer">

            <div class="admin-mini">

                <div class="admin-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>

                    <strong>
                        {{ auth()->user()->name }}
                    </strong>

                    <small>
                        Administrator
                    </small>

                </div>

            </div>

        </div>

    </aside>


    <!-- ================= MAIN ================= -->

    <div class="main-area">


        <!-- TOP NAVBAR -->

        <header class="topbar">

            <div class="mobile-brand">

                <button
                    type="button"
                    class="mobile-menu-btn"
                    onclick="toggleSidebar()"
                >
                    ☰
                </button>

                <strong>
                    Attendance Pro
                </strong>

            </div>


            <div class="topbar-right">

                <div class="date-display">

                    <span>Today</span>

                    <strong>
                        {{ now()->format('d M, Y') }}
                    </strong>

                </div>


                <div class="top-user">

                    <div class="top-avatar">

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </div>

                    <div class="top-user-info">

                        <strong>
                            {{ auth()->user()->name }}
                        </strong>

                        <span>
                            Admin
                        </span>

                    </div>

                </div>


                <form
                    action="/logout"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="logout-button"
                    >
                        Logout
                    </button>

                </form>

            </div>

        </header>


        <!-- CONTENT -->

        <main class="page-content">


            <!-- PAGE HEADER -->

            <div class="page-heading">

                <div>

                    <p class="breadcrumb">
                        Attendance Pro
                        /
                        @yield('title', 'Dashboard')
                    </p>

                    <h1>
                        @yield('title', 'Dashboard')
                    </h1>

                </div>

            </div>


            <!-- SUCCESS MESSAGE -->

            @if (session('success'))

                <div class="alert alert-success">

                    <div class="alert-icon">
                        ✓
                    </div>

                    <div>
                        {{ session('success') }}
                    </div>

                    <button
                        onclick="this.parentElement.remove()"
                        class="alert-close"
                    >
                        ×
                    </button>

                </div>

            @endif


            <!-- ERROR MESSAGE -->

            @if ($errors->any())

                <div class="alert alert-danger">

                    <div class="alert-icon">
                        !
                    </div>

                    <div>

                        @foreach ($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                    <button
                        onclick="this.parentElement.remove()"
                        class="alert-close"
                    >
                        ×
                    </button>

                </div>

            @endif


            <!-- PAGE CONTENT -->

            @yield('content')


        </main>


        <!-- FOOTER -->

        <footer class="footer">

            <span>
                © {{ date('Y') }} Attendance Pro
            </span>

            <span>
                Employee Attendance Management System
            </span>

        </footer>


    </div>

</div>


<!-- MOBILE OVERLAY -->

<div
    class="sidebar-overlay"
    onclick="toggleSidebar()"
></div>


<script>

function toggleSidebar()
{
    document
        .querySelector('.sidebar')
        .classList.toggle('show');

    document
        .querySelector('.sidebar-overlay')
        .classList.toggle('show');
}

</script>


</body>

</html>