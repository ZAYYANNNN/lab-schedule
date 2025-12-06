<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = \App\Models\Schedules::with('lab')
            ->orderBy('date')
            ->orderBy('start_time');

        if ($user->role !== 'superadmin') {
            $query->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi', $user->prodi);
            });
        }

        $schedules = $query->get();

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        if (auth()->user()->role === 'superadmin') {
            $labs = \App\Models\Lab::all();
        } else {
            $labs = \App\Models\Lab::where('prodi', auth()->user()->prodi)->get();
        }

        return view('schedules.create', compact('labs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'activity' => 'required|string|max:255',
        ]);

        $validated['created_by'] = auth()->id();
        \App\Models\Schedules::create($validated);

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(\App\Models\Schedules $schedule)
    {
        if (auth()->user()->role !== 'superadmin' && auth()->user()->prodi !== $schedule->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->role === 'superadmin') {
            $labs = \App\Models\Lab::all();
        } else {
            $labs = \App\Models\Lab::where('prodi', auth()->user()->prodi)->get();
        }

        return view('schedules.edit', compact('schedule', 'labs'));
    }

    public function update(Request $request, \App\Models\Schedules $schedule)
    {
        if (auth()->user()->role !== 'superadmin' && auth()->user()->prodi !== $schedule->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'activity' => 'required|string|max:255',
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(\App\Models\Schedules $schedule)
    {
        if (auth()->user()->role !== 'superadmin' && auth()->user()->prodi !== $schedule->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }

        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
