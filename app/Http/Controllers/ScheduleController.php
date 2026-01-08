<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedDate = $request->get('date', date('Y-m-d'));

        // Security: Non-superadmins are locked to their own prodi
        if ($user->role !== 'superadmin') {
            $selectedProdi = $user->prodi_id;
        } else {
            $selectedProdi = $request->get('prodi_id');
        }

        // Query Labs
        $labQuery = \App\Models\Lab::with('prodi');

        if ($selectedProdi) {
            $labQuery->where('prodi_id', $selectedProdi);
        } elseif ($user->role !== 'superadmin') {
            $labQuery->where('prodi_id', $user->prodi_id);
        }

        $labs = $labQuery->get();

        // Query Schedules for the selected date
        $scheduleQuery = \App\Models\Schedules::with('lab.prodi')
            ->whereDate('date', $selectedDate)
            ->orderBy('start_time');

        if ($selectedProdi) {
            $scheduleQuery->whereHas('lab', function ($q) use ($selectedProdi) {
                $q->where('prodi_id', $selectedProdi);
            });
        } elseif ($user->role !== 'superadmin') {
            $scheduleQuery->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }

        $schedules = $scheduleQuery->get();

        // Data for Create/Edit Modal
        if ($user->role === 'superadmin') {
            $allLabs = \App\Models\Lab::all();
            $allProdis = \App\Models\Prodi::all();
        } else {
            $allLabs = \App\Models\Lab::where('prodi_id', $user->prodi_id)->get();
            $allProdis = \App\Models\Prodi::where('id', $user->prodi_id)->get();
        }

        return view('schedules.index', [
            'schedules' => $schedules,
            'labs' => $labs,
            'allLabs' => $allLabs,
            'allProdis' => $allProdis,
            'selectedDate' => $selectedDate,
            'selectedProdi' => $selectedProdi
        ]);
    }

    public function create()
    {
        if (auth()->user()->role === 'superadmin') {
            $labs = \App\Models\Lab::all();
        } else {
            $labs = \App\Models\Lab::where('prodi_id', auth()->user()->prodi_id)->get();
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
        if (auth()->user()->role !== 'superadmin' && auth()->user()->prodi_id !== $schedule->lab->prodi_id) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->role === 'superadmin') {
            $labs = \App\Models\Lab::all();
        } else {
            $labs = \App\Models\Lab::where('prodi_id', auth()->user()->prodi_id)->get();
        }

        return view('schedules.edit', compact('schedule', 'labs'));
    }

    public function update(Request $request, \App\Models\Schedules $schedule)
    {
        if (auth()->user()->role !== 'superadmin' && auth()->user()->prodi_id !== $schedule->lab->prodi_id) {
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
        if (auth()->user()->role !== 'superadmin' && auth()->user()->prodi_id !== $schedule->lab->prodi_id) {
            abort(403, 'Unauthorized action.');
        }

        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
