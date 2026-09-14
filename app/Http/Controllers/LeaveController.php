<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $leaves = Leave::with(['employee.department', 'employee.shift'])
                ->latest()
                ->paginate(15);

            return view('leaves.admin-index', compact('leaves'));
        }

        $employee = $user->employee;
        if (!$employee) {
            return redirect('/dashboard')->with('error', 'No employee record linked.');
        }

        $leaves = Leave::where('employee_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('leaves.employee-index', compact('leaves', 'employee'));
    }

    public function create()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'No employee record linked.');
        }

        return view('leaves.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'No employee record linked.');
        }

        $validated = $request->validate([
            'leave_type' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        Leave::create([
            'employee_id' => $employee->id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect('/leaves')->with('success', 'Leave application submitted successfully.');
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Leave application approved.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? 'Rejected by Admin',
        ]);

        return redirect()->back()->with('success', 'Leave application rejected.');
    }
}
