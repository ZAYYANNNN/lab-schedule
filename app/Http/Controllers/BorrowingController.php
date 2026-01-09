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

        $borrowingsQuery = Borrowing::with(['user', 'lab', 'asset'])->latest();

        if ($user->role !== 'superadmin') {
            $borrowingsQuery->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }

        $borrowings = $borrowingsQuery->get();

        if ($user->role === 'superadmin') {
            $labs = Lab::with('assets')->get();
            $users = User::all();
        } else {
            $labs = Lab::where('prodi_id', $user->prodi_id)->with('assets')->get();
            $users = User::all();
        }

        return view('borrowings.index', compact('borrowings', 'labs', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lab_id' => 'required|exists:labs,id',
            'asset_id' => 'required|exists:aset_labs,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string',
        ]);

        Borrowing::create($validated);

        return back()->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Borrowing $borrowing)
    {
        return response()->json($borrowing->load(['user', 'lab', 'asset']));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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
        $borrowing->delete();
        return back()->with('success', 'Peminjaman berhasil dihapus.');
    }
}
