@extends('layouts.app')

@section('title', 'Leave Applications')

@section('content')

<div class="leaves-page">

    <div class="page-heading" style="margin-bottom: 24px;">
        <div>
            <h1>Manage Employee Leave Applications</h1>
            <p style="color: #64748b; font-size: 14px;">Review, approve or reject employee leave requests.</p>
        </div>
    </div>

    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 12px 24px;">#</th>
                        <th style="padding: 12px 24px;">Employee</th>
                        <th style="padding: 12px 24px;">Type</th>
                        <th style="padding: 12px 24px;">Dates</th>
                        <th style="padding: 12px 24px;">Reason</th>
                        <th style="padding: 12px 24px;">Status</th>
                        <th style="padding: 12px 24px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 24px; color: #94a3b8;">
                                {{ $loop->iteration }}
                            </td>
                            <td style="padding: 14px 24px;">
                                <strong>{{ $leave->employee->name }}</strong>
                                <div style="font-size: 12px; color: #64748b;">{{ $leave->employee->employee_code }} | {{ $leave->employee->department ? $leave->employee->department->name : 'N/A' }}</div>
                            </td>
                            <td style="padding: 14px 24px; text-transform: capitalize; font-weight: 600; color: #334155;">
                                {{ $leave->leave_type }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569;">
                                {{ $leave->start_date->format('d M, Y') }} - {{ $leave->end_date->format('d M, Y') }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569; max-width: 200px;">
                                {{ $leave->reason }}
                            </td>
                            <td style="padding: 14px 24px;">
                                @if($leave->status === 'approved')
                                    <span style="background: #d1fae5; color: #059669; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Approved</span>
                                @elseif($leave->status === 'rejected')
                                    <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Rejected</span>
                                @else
                                    <span style="background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Pending</span>
                                @endif
                            </td>
                            <td style="padding: 14px 24px;">
                                @if($leave->status === 'pending')
                                    <div style="display: flex; gap: 8px;">
                                        <form action="{{ route('leaves.approve', $leave->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 12px;">Approve</button>
                                        </form>

                                        <form action="{{ route('leaves.reject', $leave->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 12px;">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-size: 13px;">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 24px; text-align: center; color: #94a3b8;">
                                No leave applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaves->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0;">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
