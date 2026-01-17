<?php

namespace App\Http\Controllers;

use App\Models\LabRental;
use App\Models\Lab;
use App\Models\Prodi;
use Illuminate\Http\Request;

class LabRentalController extends Controller
{
    private function checkAccess()
    {
        $user = auth()->user();

        // Superadmin: OK
        if ($user->role === 'superadmin') {
            return;
        }

        // Admin: Cek apakah assign ke Lab tipe 'sewa'
        if ($user->role === 'admin' && $user->lab_id) {
            $lab = Lab::find($user->lab_id);
            if ($lab && $lab->type === 'sewa') {
                return;
            }
        }

        abort(403, 'Menu Sewa Lab hanya untuk Admin yang ditugaskan di Lab tipe Sewa.');
    }

    public function index()
    {
        $this->checkAccess();
        $user = auth()->user();

        $rentalsQuery = LabRental::with(['lab', 'user'])->latest();

        if ($user->role !== 'superadmin') {
            $rentalsQuery->whereHas('lab', function ($q) use ($user) {
                if ($user->lab_id) {
                    $q->where('id', $user->lab_id);
                } elseif ($user->prodi_id) {
                    $q->where('prodi_id', $user->prodi_id);
                }
            });
        }

        if (request()->has('status') && request('status') !== 'all') {
            $rentalsQuery->where('status', request('status'));
        }

        $rentals = $rentalsQuery->get();

        $labs = [];
        if ($user->role === 'admin') {
            if ($user->lab_id) {
                $labs = Lab::where('id', $user->lab_id)->get();
            } elseif ($user->prodi_id) {
                $labs = Lab::where('prodi_id', $user->prodi_id)->get();
            }
        } elseif ($user->role === 'superadmin') {
            $labs = Lab::where('type', 'sewa')->get();
        }

        // Fetch return dates for markers
        $returnDates = $rentalsQuery->clone()
            ->reorder()
            ->whereIn('status', ['pending', 'approved'])
            ->selectRaw('DISTINCT return_date')
            ->pluck('return_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        return view('lab_rentals.index', compact('rentals', 'labs', 'returnDates'));
    }

    public function store(Request $request)
    {
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
            'status' => 'required|in:pending,approved,rejected,completed',
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
