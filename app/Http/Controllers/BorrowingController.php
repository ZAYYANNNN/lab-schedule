<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Lab;
use App\Models\User;
use App\Models\AssetLab;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $borrowingsQuery = Borrowing::with(['lab', 'asset'])->latest();

        if ($user->role !== 'superadmin') {
            $borrowingsQuery->whereHas('lab', function ($q) use ($user) {
                if ($user->lab_id) {
                    $q->where('id', $user->lab_id);
                } elseif ($user->prodi_id) {
                    $q->where('prodi_id', $user->prodi_id);
                }
            });
        }

        if (request()->has('status') && request('status') !== 'all') {
            $borrowingsQuery->where('status', request('status'));
        }

        $borrowings = $borrowingsQuery->get();

        $prodis = [];
        if ($user->role === 'superadmin') {
            $prodis = \App\Models\Prodi::with(['labs.assets'])->get();
        }

        $labs = [];
        if ($user->role === 'admin') {
            if ($user->lab_id) {
                $labs = Lab::where('id', $user->lab_id)->with('assets')->get();
            } elseif ($user->prodi_id) {
                $labs = Lab::where('prodi_id', $user->prodi_id)->with('assets')->get();
            }
        } elseif ($user->role === 'superadmin') {
            $labs = Lab::with('assets')->get();
        }

        // Fetch return dates for markers
        $returnDates = $borrowingsQuery->clone()
            ->reorder()
            ->whereIn('status', ['pending', 'approved'])
            ->selectRaw('DISTINCT return_date')
            ->pluck('return_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        return view('borrowings.index', compact('borrowings', 'labs', 'prodis', 'returnDates'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'lab_id' => 'required|exists:labs,id',
            'asset_id' => 'required|exists:aset_labs,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Borrowing::create($validated);

        return back()->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Borrowing $borrowing)
    {
        return response()->json($borrowing->load(['user', 'lab', 'asset']));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'lab_id' => 'required|exists:labs,id',
            'asset_id' => 'required|exists:aset_labs,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'status' => 'required|in:pending,approved,rejected,returned',
            'notes' => 'nullable|string',
        ]);

        $borrowing->update($validated);

        return back()->with('success', 'Peminjaman berhasil diupdate.');
    }

    public function destroy(Borrowing $borrowing)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Akses ditolak.');
        }

        $borrowing->delete();
        return back()->with('success', 'Peminjaman berhasil dihapus.');
    }
}
