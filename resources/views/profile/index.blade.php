@extends('layouts.app')

@section('title', 'My Profile & Settings')

@section('content')

<div class="profile-page">

    <div class="page-heading" style="margin-bottom: 24px;">
        <div>
            <h1>My Profile & Account Settings</h1>
            <p style="color: #64748b; font-size: 14px;">View your official profile details and change your account password.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

        <!-- PROFILE DETAILS -->
        <div style="background: #ffffff; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                Personal & Job Information
            </h2>

            <div style="display: flex; flex-direction: column; gap: 14px; font-size: 14px;">
                <div>
                    <span style="color: #64748b; font-size: 12px; font-weight: 600; display: block;">Employee ID (Read-only)</span>
                    <strong style="font-size: 16px; color: #6366f1;">{{ $employee ? $employee->employee_code : 'N/A' }}</strong>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px; font-weight: 600; display: block;">Full Name</span>
                    <strong style="color: #0f172a;">{{ $user->name }}</strong>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px; font-weight: 600; display: block;">Email Address</span>
                    <span style="color: #334155;">{{ $user->email }}</span>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px; font-weight: 600; display: block;">Phone</span>
                    <span style="color: #334155;">{{ $employee ? $employee->phone : 'N/A' }}</span>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px; font-weight: 600; display: block;">Department</span>
                    <span style="color: #334155;">{{ $employee && $employee->department ? $employee->department->name : 'N/A' }}</span>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px; font-weight: 600; display: block;">Designation</span>
                    <span style="color: #334155;">{{ $employee && $employee->designation ? $employee->designation : 'N/A' }}</span>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px; font-weight: 600; display: block;">Assigned Shift</span>
                    <span style="color: #334155;">
                        {{ $employee && $employee->shift ? $employee->shift->name . ' (' . substr($employee->shift->start_time, 0, 5) . ' - ' . substr($employee->shift->end_time, 0, 5) . ')' : 'N/A' }}
                    </span>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px; font-weight: 600; display: block;">Joining Date</span>
                    <span style="color: #334155;">{{ $employee && $employee->joining_date ? $employee->joining_date->format('d M, Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- CHANGE PASSWORD -->
        <div style="background: #ffffff; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                Change Password
            </h2>

            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 6px;">Current Password *</label>
                    <input type="password" name="current_password" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 6px;">New Password *</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 6px;">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                </div>

                <button type="submit" style="background: #6366f1; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 14px;">
                    Update Password
                </button>
            </form>
        </div>

    </div>

</div>

@endsection
