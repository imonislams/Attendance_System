@extends('layouts.app')

@section('title', 'My Leave Applications')

@section('content')

<div class="leaves-page">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1>My Leave Applications</h1>
            <p style="color: #64748b; font-size: 14px;">Apply for leave and view application statuses.</p>
        </div>

        <a href="{{ route('leaves.create') }}" style="background: #6366f1; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 700; text-decoration: none;">
            + Apply for Leave
        </a>
    </div>

    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 12px 24px;">#</th>
                        <th style="padding: 12px 24px;">Leave Type</th>
                        <th style="padding: 12px 24px;">From Date</th>
                        <th style="padding: 12px 24px;">To Date</th>
                        <th style="padding: 12px 24px;">Reason</th>
                        <th style="padding: 12px 24px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 24px; color: #94a3b8;">
                                {{ $loop->iteration }}
                            </td>
                            <td style="padding: 14px 24px; font-weight: 600; color: #0f172a; text-transform: capitalize;">
                                {{ $leave->leave_type }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569;">
                                {{ $leave->start_date->format('d M, Y') }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569;">
                                {{ $leave->end_date->format('d M, Y') }}
                            </td>
                            <td style="padding: 14px 24px; color: #475569;">
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 24px; text-align: center; color: #94a3b8;">
                                You have not submitted any leave applications yet.
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
