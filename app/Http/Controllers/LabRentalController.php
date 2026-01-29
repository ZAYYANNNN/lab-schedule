<?php

namespace App\Http\Controllers;

use App\Models\LabRental;
use App\Models\Lab;
use App\Models\Prodi;
use App\Models\RentalStatus;
use Illuminate\Http\Request;

class LabRentalController extends Controller
{
    private function checkAccess()
    {
        $user = auth()->user();
        if (!$user->hasRentalAccess()) {
            abort(403, 'Anda tidak memiliki akses ke menu Kalibrasi.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAccess();
        $user = auth()->user();
        $q = $request->search;

        // Get Calibration Activity Type ID
        $calibrationType = \App\Models\ActivityType::where('name', 'Kalibrasi')->first();

        // If activity type doesn't exist yet, we can't show anything (should be seeded)
        if (!$calibrationType) {
            $schedules = [];
        } else {
            $schedules = \App\Models\Schedules::query()
                ->with(['lab', 'creator', 'activityType'])
                ->where('activity_type_id', $calibrationType->id)
                ->when($q, function ($query) use ($q) {
                    $query->whereHas('lab', function ($subQuery) use ($q) {
                        $subQuery->where('name', 'like', "%{$q}%");
                    });
                })
                ->when($user->role !== 'superadmin', function ($query) use ($user) {
                    if ($user->role === 'admin') {
                        $query->whereHas('lab', function ($q) use ($user) {
                            $q->where('admin_id', $user->id);
                        });
                    } elseif ($user->prodi_id) {
                        $query->whereHas('lab', function ($q) use ($user) {
                            $q->where('prodi_id', $user->prodi_id);
                        });
                    }
                })
                ->get()
                ->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'title' => $schedule->activity . ' (' . ($schedule->lab->name ?? 'Unknown Lab') . ')',
                        'start' => $schedule->date . 'T' . $schedule->start_time,
                        'end' => $schedule->date . 'T' . $schedule->end_time,
                        // Fullcalendar properties
                        'extendedProps' => [
                            'lab_name' => $schedule->lab->name ?? '-',
                            'activity' => $schedule->activity,
                            'creator' => $schedule->creator->name ?? '-',
                            'description' => 'Kalibrasi di ' . ($schedule->lab->name ?? '-')
                        ],
                        'color' => '#8b5cf6' // Purple
                    ];
                });
        }

        return view('lab_rentals.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        dd($request->all());

        $this->checkAccess();

        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'lab_id' => 'required|exists:labs,id',
            'purpose' => 'required|string',
            'rental_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:rental_date',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        // Default status: pending
        $pendingStatus = RentalStatus::where('slug', 'pending')->first();
        if ($pendingStatus) {
            $validated['status_id'] = $pendingStatus->id;
        }

        LabRental::create($validated);

        return back()->with('success', 'Penyewaan lab berhasil dicatat.');

    }

    public function update(Request $request, LabRental $labRental)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'lab_id' => 'required|exists:labs,id',
            'purpose' => 'required|string',
            'rental_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:rental_date',
            'status_id' => 'required|exists:rental_statuses,id',
            'notes' => 'nullable|string',
        ]);

        $labRental->update($validated);

        return back()->with('success', 'Penyewaan lab berhasil diupdate.');
    }

    public function destroy(LabRental $labRental)
    {
        $this->checkAccess();

        $labRental->delete();
        return back()->with('success', 'Penyewaan lab berhasil dihapus.');
    }
}
