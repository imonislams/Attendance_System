<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginId = trim($request->input('login_id'));
        $password = $request->input('password');

        $user = null;

        if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginId)->first();
        } else {
            $employee = Employee::where('employee_code', $loginId)->first();
            if ($employee) {
                if ($employee->status !== 'active') {
                    return back()
                        ->withInput($request->only('login_id'))
                        ->withErrors([
                            'login_id' => 'Your account is inactive. Please contact administrator.'
                        ]);
                }
                $user = $employee->user;
            }
        }

        if (!$user) {
            return back()
                ->withInput($request->only('login_id'))
                ->withErrors([
                    'login_id' => 'Invalid Employee ID/Email or password.'
                ]);
        }

        if ($user->employee && $user->employee->status !== 'active') {
            return back()
                ->withInput($request->only('login_id'))
                ->withErrors([
                    'login_id' => 'Your employee account is inactive. Please contact administrator.'
                ]);
        }

        if (Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));

            $request->session()->regenerate();

            return redirect('/dashboard')
                ->with('success', 'Login successful!');
        }

        return back()
            ->withInput($request->only('login_id'))
            ->withErrors([
                'login_id' => 'Invalid Employee ID/Email or password.'
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Logged out successfully!');
    }
}
