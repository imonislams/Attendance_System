@extends('layouts.app')

@section('title', 'Apply for Leave')

@section('content')

<div class="leaves-create-page">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1>Apply for Leave</h1>
            <p style="color: #64748b; font-size: 14px;">Fill out the leave request form below.</p>
        </div>

        <a href="{{ route('leaves.index') }}" style="color: #6366f1; text-decoration: none; font-weight: 600;">
            ← Back to Leaves
        </a>
    </div>

    <div style="background: #ffffff; padding: 28px; border-radius: 12px; border: 1px solid #e2e8f0; max-width: 600px;">
        <form action="{{ route('leaves.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 6px;">Leave Type *</label>
                <select name="leave_type" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                    <option value="">Select Leave Type</option>
                    <option value="casual" {{ old('leave_type') === 'casual' ? 'selected' : '' }}>Casual Leave</option>
                    <option value="sick" {{ old('leave_type') === 'sick' ? 'selected' : '' }}>Sick Leave</option>
                    <option value="annual" {{ old('leave_type') === 'annual' ? 'selected' : '' }}>Annual Leave</option>
                    <option value="unpaid" {{ old('leave_type') === 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 6px;">Start Date *</label>
                    <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 6px;">End Date *</label>
                    <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 6px;">Reason *</label>
                <textarea name="reason" rows="4" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;" placeholder="Provide reason for leave application...">{{ old('reason') }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" style="background: #6366f1; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 15px;">
                    Submit Application
                </button>
                <a href="{{ route('leaves.index') }}" style="color: #64748b; text-decoration: none; font-weight: 600; font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div>

</div>

@endsection
