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

    public function index()
    {
        $this->checkAccess();
        $user = auth()->user();

        $rentalsQuery = LabRental::with(['lab', 'user', 'status'])->latest();

        if ($user->role !== 'superadmin') {
            $rentalsQuery->whereHas('lab', function ($q) use ($user) {
                if ($user->role === 'admin') {
                    $q->where('admin_id', $user->id);
                } elseif ($user->prodi_id) {
                    $q->where('prodi_id', $user->prodi_id);
                }
            });
        }

        if (request()->has('status') && request('status') !== 'all') {
            $statusSlug = request('status');
            $rentalsQuery->whereHas('status', function ($q) use ($statusSlug) {
                $q->where('slug', $statusSlug);
            });
        }

        $rentals = $rentalsQuery->get();

        $labs = [];
        if ($user->role === 'admin') {
            $labs = Lab::where('admin_id', $user->id)->get();
        } elseif ($user->role === 'superadmin') {
            $labs = Lab::whereHas('type', function ($q) {
                $q->where('slug', 'kalibrasi');
            })->get();
        }

        // Fetch return dates for markers
        $returnDates = $rentalsQuery->clone()
            ->reorder()
            ->whereHas('status', function ($q) {
                $q->whereIn('slug', ['pending', 'approved']);
            })
            ->selectRaw('DISTINCT return_date')
            ->pluck('return_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $rentalStatuses = RentalStatus::all();

        return view('lab_rentals.index', compact('rentals', 'labs', 'returnDates', 'rentalStatuses'));
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
