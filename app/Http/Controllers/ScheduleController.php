<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedDate = $request->get('date', date('Y-m-d'));

        // Fetch all available Prodis and Labs first to determine defaults
        if ($user->role === 'superadmin') {
            $allProdis = \App\Models\Prodi::with('labs')->get();
            $allLabs = \App\Models\Lab::all();
        } else {
            $allProdis = \App\Models\Prodi::where('id', $user->prodi_id)->with('labs')->get();
            $allLabs = \App\Models\Lab::where('prodi_id', $user->prodi_id)->get();
        }

        $allProdiIds = $allProdis->pluck('id')->toArray();
        $allLabIds = $allLabs->pluck('id')->toArray();

        // Check if filters were explicitly submitted (even if empty)
        // 'filter_submitted' can be a hidden input in the filter form to distinguish initial load from explicit empty submission
        $hasFilterRequest = $request->has('prodi_ids') || $request->has('lab_ids') || $request->has('filter_submitted');

        if ($hasFilterRequest) {
            $selectedProdiIds = $request->get('prodi_ids', []);
            $selectedLabIds = $request->get('lab_ids', []);
        } else {
            // Default: All checked
            $selectedProdiIds = $allProdiIds;
            $selectedLabIds = $allLabIds;
        }

        // Ensure they are arrays
        if (!is_array($selectedProdiIds))
            $selectedProdiIds = [$selectedProdiIds];
        if (!is_array($selectedLabIds))
            $selectedLabIds = [$selectedLabIds];

        // Security: Non-superadmins are locked to their own prodi
        // This logic is now integrated into the initial fetching of $allProdis and $allLabs,
        // and then applied to $selectedProdiIds and $selectedLabIds if the user is not a superadmin.
        if ($user->role !== 'superadmin') {
            // Ensure selected prodi IDs only include the user's prodi
            $selectedProdiIds = array_intersect($selectedProdiIds, [$user->prodi_id]);
            // Ensure selected lab IDs only include labs belonging to the user's prodi
            $userProdiLabIds = \App\Models\Lab::where('prodi_id', $user->prodi_id)->pluck('id')->toArray();
            $selectedLabIds = array_intersect($selectedLabIds, $userProdiLabIds);
        }

        // Query Labs for the grid headers
        $labQuery = \App\Models\Lab::with('prodi');

        if (!empty($selectedLabIds) || !empty($selectedProdiIds)) {
            $labQuery->where(function ($q) use ($selectedLabIds, $selectedProdiIds) {
                if (!empty($selectedLabIds)) {
                    $q->orWhereIn('id', $selectedLabIds);
                }
                if (!empty($selectedProdiIds)) {
                    $q->orWhereIn('prodi_id', $selectedProdiIds);
                }
            });
        } else {
            // Explicitly show nothing if nothing is checked
            $labQuery->whereRaw('1 = 0');
        }

        $labs = $labQuery->get();

        // Query Schedules for the selected date
        $scheduleQuery = \App\Models\Schedules::with(['lab.prodi', 'creator'])
            ->whereDate('date', $selectedDate)
            ->orderBy('start_time');

        if (!empty($selectedLabIds) || !empty($selectedProdiIds)) {
            $scheduleQuery->whereHas('lab', function ($q) use ($selectedLabIds, $selectedProdiIds) {
                $q->where(function ($sq) use ($selectedLabIds, $selectedProdiIds) {
                    if (!empty($selectedLabIds)) {
                        $sq->orWhereIn('id', $selectedLabIds);
                    }
                    if (!empty($selectedProdiIds)) {
                        $sq->orWhereIn('prodi_id', $selectedProdiIds);
                    }
                });
            });
        } else {
            // Explicitly show nothing if nothing is checked
            $scheduleQuery->whereRaw('1 = 0');
        }

        $schedules = $scheduleQuery->get();
        
        // Fetch all dates that have schedules for the markers
        $scheduledDates = \App\Models\Schedules::query()
            ->selectRaw('DISTINCT date')
            ->whereHas('lab', function ($q) use ($selectedLabIds, $selectedProdiIds) {
                $q->where(function ($sq) use ($selectedLabIds, $selectedProdiIds) {
                    if (!empty($selectedLabIds)) {
                        $sq->orWhereIn('id', $selectedLabIds);
                    }
                    if (!empty($selectedProdiIds)) {
                        $sq->orWhereIn('prodi_id', $selectedProdiIds);
                    }
                });
            })
            ->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        // $allProdis and $allLabs are already fetched at the beginning of the method
        // and are correctly scoped by user role.

        return view('schedules.index', [
            'schedules' => $schedules,
            'labs' => $labs,
            'allLabs' => $allLabs,
            'allProdis' => $allProdis,
            'selectedDate' => $selectedDate,
            'selectedProdiIds' => $selectedProdiIds,
            'selectedLabIds' => $selectedLabIds,
            'scheduledDates' => $scheduledDates
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

        // Conflict Detection
        $conflict = \App\Models\Schedules::where('lab_id', $request->lab_id)
            ->whereDate('date', $request->date)
            ->where(function ($query) use ($request) {
                // Check for overlapping time
                // A new schedule (StartA, EndA) overlaps with existing (StartB, EndB) if:
                // StartA < EndB AND EndA > StartB
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['collision' => 'Jadwal bertabrakan dengan kegiatan lain di lab ini pada waktu tersebut.'])->withInput();
        }

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
