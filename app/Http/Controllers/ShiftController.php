<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::latest()->get();

        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shifts,name',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_after' => 'required',
        ]);

        Shift::create($validated);

        return redirect('/shifts')
            ->with('success', 'Shift created successfully!');
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);

        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shifts,name,' . $shift->id,
            'start_time' => 'required',
            'end_time' => 'required',
            'late_after' => 'required',
        ]);

        $shift->update($validated);

        return redirect('/shifts')
            ->with('success', 'Shift updated successfully!');
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);

        $shift->delete();

        return redirect('/shifts')
            ->with('success', 'Shift deleted successfully!');
    }
}