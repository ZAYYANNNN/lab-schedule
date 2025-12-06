<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'superadmin') {
            abort(403, 'Unauthorized action.');
        }

        $borrowings = \App\Models\Borrowing::with(['user', 'lab', 'asset'])
            ->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi', $user->prodi);
            })
            ->latest()
            ->get();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $labs = \App\Models\Lab::where('prodi', auth()->user()->prodi)->with('assets')->get();
        $users = \App\Models\User::all(); // Assuming admin can select any user to borrow
        return view('borrowings.create', compact('labs', 'users'));
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

        \App\Models\Borrowing::create($validated);

        return redirect()->route('borrowings.index')->with('success', 'Borrowing recorded successfully.');
    }

    public function edit(\App\Models\Borrowing $borrowing)
    {
        if (auth()->user()->prodi !== $borrowing->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }
        $labs = \App\Models\Lab::where('prodi', auth()->user()->prodi)->with('assets')->get();
        $users = \App\Models\User::all();
        return view('borrowings.edit', compact('borrowing', 'labs', 'users'));
    }

    public function update(Request $request, \App\Models\Borrowing $borrowing)
    {
        if (auth()->user()->prodi !== $borrowing->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }
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

        return redirect()->route('borrowings.index')->with('success', 'Borrowing updated successfully.');
    }

    public function destroy(\App\Models\Borrowing $borrowing)
    {
        if (auth()->user()->prodi !== $borrowing->lab->prodi) {
            abort(403, 'Unauthorized action.');
        }
        $borrowing->delete();
        return redirect()->route('borrowings.index')->with('success', 'Borrowing deleted successfully.');
    }
}
