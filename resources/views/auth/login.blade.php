<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Attendance Pro</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body class="login-page">

    <div class="login-box">

        <div class="login-logo">

            <div class="login-logo-icon">
                AP
            </div>

        </div>

        <h1>Attendance Pro</h1>

        <p class="login-subtitle">
            Employee Attendance Management System
        </p>

        <div class="login-divider"></div>


        @if (session('success'))

            <div class="alert alert-success">

                <div class="alert-icon">
                    ✓
                </div>

                <div>
                    {{ session('success') }}
                </div>

            </div>

        @endif


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

            </div>

        @endif


        <form action="/login" method="POST">

            @csrf


            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    autofocus
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-submit"
            >
                Sign In
            </button>

        </form>


        <div class="login-footer">

            <span>
                Secure Attendance Management
            </span>

        </div>

    </div>

</body>

</html>