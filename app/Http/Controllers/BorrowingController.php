<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Lab;
use App\Models\User;
use App\Models\AssetLab;
use App\Models\BorrowingStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Base Query (Role/Lab restrictions only)
        $baseQuery = Borrowing::with(['lab', 'asset', 'status'])->latest();

        if ($user->role !== 'superadmin') {
            $baseQuery->whereHas('lab', function ($q) use ($user) {
                // Admin sees their managed lab, or falls back to prodi
                $managedLab = Lab::where('admin_id', $user->id)->first();
                if ($managedLab) {
                    $q->where('id', $managedLab->id);
                } elseif ($user->prodi_id) {
                    $q->where('prodi_id', $user->prodi_id);
                }
            });
        }

        // Clone for Alert/Stats (ignoring status filter)
        $alertQuery = $baseQuery->clone();

        // Apply filters for Main Table
        $itemQuery = $baseQuery->clone();
        if (request()->has('status') && request('status') !== 'all') {
            $statusSlug = request('status');
            $itemQuery->whereHas('status', function ($q) use ($statusSlug) {
                $q->where('slug', $statusSlug);
            });
        }

        $borrowings = $itemQuery->get();

        // Load all relevant items for Alert Calculations (Pending, Approved, Returned)
        $allAlertItems = $alertQuery->whereHas('status', function ($q) {
            $q->whereIn('slug', ['pending', 'approved', 'returned']);
        })->get();

        // Check for overdue status (just for property setting if needed)
        // Check for overdue status (just for property setting if needed)
        foreach ($borrowings as $b) {
            // Check slug via relationship
            if ($b->status && $b->status->slug !== 'returned' && $b->status->slug !== 'rejected') {
                if ($b->isOverdue()) {
                    $b->is_overdue_display = true;
                }
            }
        }

        $prodis = [];
        if ($user->role === 'superadmin') {
            $prodis = \App\Models\Prodi::with(['labs.assets'])->get();
        }

        $labs = [];
        $labs = [];
        if ($user->role === 'admin') {
            // Find managed lab first
            $managedLab = Lab::where('admin_id', $user->id)->first();

            if ($managedLab) {
                // If admin manages a lab, they see THAT lab
                $labs = Lab::where('id', $managedLab->id)->with('assets')->get();
            } elseif ($user->prodi_id) {
                // Fallback: If not assigned to a lab, see all libs in prodi (Legacy/Super-Admin-like admin)
                $labs = Lab::where('prodi_id', $user->prodi_id)->with('assets')->get();
            }
        } elseif ($user->role === 'superadmin') {
            $labs = Lab::with('assets')->get();
        }

        // Fetch return dates for markers (from all relevant items)
        $returnDates = $alertQuery->clone()
            ->reorder()
            ->whereHas('status', function ($q) {
                $q->whereIn('slug', ['pending', 'approved']);
            })
            ->selectRaw('DISTINCT return_date')
            ->pluck('return_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        // 1. Deadline Today: Status approved/pending AND return_date is today
        $deadlineItems = $allAlertItems->filter(function ($b) {
            return $b->status && ($b->status->slug === 'approved' || $b->status->slug === 'pending') &&
                $b->return_date->isToday();
        });

        // 2. Overdue Items: Status approved/pending AND return_time has passed
        $overdueItems = $allAlertItems->filter(function ($b) {
            return $b->isOverdue();
        });

        // 3. Late Returns: Status returned but actual_return_datetime > expected
        $lateReturnedItems = $allAlertItems->filter(function ($b) {
            return $b->status && $b->status->slug === 'returned' && $b->getLateDuration() !== null;
        });

        // Combine for "Jatuh Tempo & Keterlambatan"
        // Priority: Overdue > Deadline Today > Late Returned
        $attentionItems = $overdueItems->merge($deadlineItems)->merge($lateReturnedItems);

        // Pending Items (for reference if needed)
        $pendingItems = $allAlertItems->filter(fn($b) => $b->status && $b->status->slug === 'pending');

        $allLabsFlat = [];
        if ($user->role === 'superadmin') {
            $allLabsFlat = Lab::with('assets')->get();
        } else {
            $allLabsFlat = $labs;
        }

        // Items for the "Keterlambatan Pengembalian" table (bottom table)
        // Shows all overdue and late returned items, independent of main filter
        $lateReportItems = $overdueItems->merge($lateReturnedItems)->values();

        $borrowingStatuses = BorrowingStatus::all();

        return view('borrowings.index', compact(
            'borrowings',
            'labs',
            'prodis',
            'returnDates',
            'deadlineItems',
            'overdueItems',
            'lateReturnedItems',
            'attentionItems',
            'pendingItems',
            'allLabsFlat',
            'lateReportItems',
            'borrowingStatuses'
        ));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'nomor_identitas' => 'required|string|max:255|min:5',
            'lab_id' => 'required|exists:labs,id',
            'asset_id' => 'required|exists:aset_labs,id',
            'borrow_date' => 'required|date',
            'borrow_time' => 'required|date_format:H:i',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
            'status_id' => 'required|exists:borrowing_statuses,id',
        ]);

        $validated['user_id'] = auth()->id();

        Borrowing::create($validated);

        return back()->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Borrowing $borrowing)
    {
        return response()->json($borrowing->load(['user', 'lab', 'asset', 'status']));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'nomor_identitas' => 'required|string|max:255|min:5',
            'lab_id' => 'required|exists:labs,id',
            'asset_id' => 'required|exists:aset_labs,id',
            'borrow_date' => 'required|date',
            'borrow_time' => 'required|date_format:H:i',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'status_id' => 'required|exists:borrowing_statuses,id',
            'notes' => 'nullable|string',
        ]);

        // Check if status is becoming 'returned' (need to find slug for ID)
        $returnedStatusId = BorrowingStatus::where('slug', 'returned')->value('id');

        // If status changes to returned, set actual_return_datetime if not already set
        if ((int) $validated['status_id'] === (int) $returnedStatusId && $borrowing->status_id !== $returnedStatusId) {
            $validated['actual_return_datetime'] = now();
        }

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
