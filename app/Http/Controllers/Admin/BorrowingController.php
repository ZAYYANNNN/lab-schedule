<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Lab;
use App\Models\AsetLab;
use App\Models\User;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    /**
     * Menampilkan daftar semua Peminjaman yang terkait dengan Lab Prodi Admin.
     */
    public function index()
    {
        $userProdiId = auth()->user()->prodi_id;
        
        // 1. Ambil ID semua Lab milik Prodi Admin
        $labIds = Lab::where('prodi_id', $userProdiId)->pluck('id');
        
        // 2. Ambil ID semua Aset yang ada di Lab tersebut
        $assetIds = AsetLab::whereIn('lab_id', $labIds)->pluck('id');

        // 3. Ambil semua Peminjaman yang terkait dengan Aset ID tersebut
        $borrowings = Borrowing::whereIn('asset_lab_id', $assetIds)
                               ->with('asset.lab', 'borrower')
                               ->latest()
                               ->paginate(15);

        return view('admin.borrowings.index', compact('borrowings'));
    }

    /**
     * Menampilkan form untuk membuat Peminjaman baru.
     */
    public function create()
    {
        $userProdiId = auth()->user()->prodi_id;
        
        // Hanya tampilkan Aset yang dimiliki oleh Prodi Admin
        $assets = AsetLab::whereHas('lab', function ($query) use ($userProdiId) {
            $query->where('prodi_id', $userProdiId);
        })->get();
        
        // Asumsi: Mahasiswa yang bisa meminjam (Anda mungkin perlu menyesuaikan query ini)
        $borrowers = User::where('role', 'mahasiswa')->get(['id', 'name', 'nim']);
        
        return view('admin.borrowings.create', compact('assets', 'borrowers'));
    }

    /**
     * Menyimpan Peminjaman baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_lab_id' => 'required|exists:aset_labs,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date|before_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string',
        ]);
        
        $asset = AsetLab::find($request->asset_lab_id);

        // --- VERIFIKASI KEPEMILIKAN ASET ---
        if ($asset->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Aset yang dipilih bukan milik Prodi Anda.');
        }

        // --- VERIFIKASI KETERSEDIAAN STOK ---
        $available = $asset->quantity - Borrowing::where('asset_lab_id', $asset->id)
                                                ->whereIn('status', ['Dipinjam', 'Terlambat'])
                                                ->sum('quantity');

        if ($request->quantity > $available) {
            return back()->withInput()->withErrors(['quantity' => "Jumlah yang dipinjam melebihi stok tersedia. Stok saat ini: {$available} unit."]);
        }
        
        $data = $validated;
        $data['status'] = 'Dipinjam'; // Set status awal

        Borrowing::create($data);

        return redirect()->route('admin.borrowings.index')
                         ->with('success', 'Peminjaman berhasil dicatat.');
    }

    /**
     * Menampilkan form untuk mengedit Peminjaman (biasanya hanya untuk mengupdate status/tanggal kembali).
     */
    public function edit(Borrowing $borrowing)
    {
        // --- VERIFIKASI KEPEMILIKAN ---
        if ($borrowing->asset->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Peminjaman ini bukan terkait dengan Lab Prodi Anda.');
        }

        // Ambil data assets dan borrowers lagi jika ada keperluan untuk mengubah
        $userProdiId = auth()->user()->prodi_id;
        $assets = AsetLab::whereHas('lab', function ($query) use ($userProdiId) {
            $query->where('prodi_id', $userProdiId);
        })->get();
        $borrowers = User::where('role', 'mahasiswa')->get(['id', 'name', 'nim']);

        return view('admin.borrowings.edit', compact('borrowing', 'assets', 'borrowers'));
    }

    /**
     * Memperbarui Peminjaman.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        // --- VERIFIKASI KEPEMILIKAN ---
        if ($borrowing->asset->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Peminjaman ini bukan terkait dengan Lab Prodi Anda.');
        }

        $validated = $request->validate([
            'asset_lab_id' => 'required|exists:aset_labs,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date|before_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'actual_return_date' => 'nullable|date|after_or_equal:borrow_date',
            'status' => ['required', Rule::in(['Dipinjam', 'Dikembalikan', 'Terlambat', 'Dibatalkan'])],
            'notes' => 'nullable|string',
        ]);
        
        // Lakukan verifikasi stok jika asset_lab_id atau quantity berubah DAN status masih 'Dipinjam'
        $oldQuantity = $borrowing->quantity;
        $newQuantity = $request->quantity;
        $newAssetId = $request->asset_lab_id;
        $oldAssetId = $borrowing->asset_lab_id;

        if ($validated['status'] == 'Dipinjam' || $validated['status'] == 'Terlambat') {
            
            // 1. Tentukan asset yang dicek (bisa asset lama atau baru)
            $assetToCheck = AsetLab::find($newAssetId);

            // 2. Hitung stok yang sedang dipinjam (kecuali peminjaman ini)
            $borrowedSum = Borrowing::where('asset_lab_id', $assetToCheck->id)
                                    ->whereIn('status', ['Dipinjam', 'Terlambat'])
                                    ->where('id', '!=', $borrowing->id) // Kecualikan peminjaman yang sedang diedit
                                    ->sum('quantity');

            // 3. Hitung ketersediaan dan total kebutuhan baru
            $available = $assetToCheck->quantity - $borrowedSum;

            if ($newQuantity > $available) {
                return back()->withInput()->withErrors(['quantity' => "Jumlah yang diminta ($newQuantity) melebihi stok yang tersedia saat ini ({$available} unit) untuk aset ini."]);
            }
        }

        // Jika status diubah menjadi 'Dikembalikan', otomatis set actual_return_date jika belum ada
        if ($validated['status'] === 'Dikembalikan' && empty($validated['actual_return_date'])) {
            $validated['actual_return_date'] = Carbon::now()->toDateString();
        }

        $borrowing->update($validated);

        return redirect()->route('admin.borrowings.index')
                         ->with('success', 'Data Peminjaman berhasil diperbarui.');
    }

    /**
     * Menghapus Peminjaman.
     */
    public function destroy(Borrowing $borrowing)
    {
        // --- VERIFIKASI KEPEMILIKAN ---
        if ($borrowing->asset->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Peminjaman ini bukan terkait dengan Lab Prodi Anda.');
        }
        
        $borrowing->delete();

        return redirect()->route('admin.borrowings.index')
                         ->with('success', 'Data Peminjaman berhasil dihapus.');
    }
}