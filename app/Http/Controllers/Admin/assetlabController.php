<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\assetlab;
use App\Models\Lab;

class assetlabController extends Controller
{
    /**
     * Menampilkan daftar semua Aset Lab milik Prodi Admin yang sedang login.
     */
    public function index()
    {
        $userProdiId = auth()->user()->prodi_id;
        
        // Ambil ID semua lab milik prodi ini
        $labIds = Lab::where('prodi_id', $userProdiId)->pluck('id');

        // Ambil semua aset yang lab_id-nya ada di $labIds
        $assets = Assetlab::whereIn('lab_id', $labIds)
                         ->with('lab')
                         ->latest()
                         ->paginate(15);

        return view('admin.assetlab.index', compact('assets'));
    }

    /**
     * Menampilkan form untuk membuat Aset Lab baru.
     */
    public function create()
    {
        $userProdiId = auth()->user()->prodi_id;
        // Hanya tampilkan Lab yang dimiliki oleh Prodi Admin
        $labs = Lab::where('prodi_id', $userProdiId)->get();

        if ($labs->isEmpty()) {
            // Ini akan terjadi jika Superadmin belum mendaftarkan Lab untuk Prodi ini
            return redirect()->route('admin.dashboard')
                             ->with('error', 'Anda tidak dapat menambahkan aset karena belum ada Lab yang terdaftar untuk Prodi Anda.');
        }

        return view('admin.assetlab.create', compact('labs'));
    }

    /**
     * Menyimpan Aset Lab baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'condition' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // --- VERIFIKASI KEPEMILIKAN LAB (KRUSIAL) ---
        $lab = Lab::find($request->lab_id);
        if (!$lab || $lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Lab tidak valid atau bukan milik Prodi Anda.');
        }
        
        Assetlab::create($validated);

        return redirect()->route('admin.assetlab.index')
                         ->with('success', 'Aset Lab berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit Aset Lab.
     */
    public function edit(Assetlab $assetlab)
    {
        // --- VERIFIKASI KEPEMILIKAN ASET (KRUSIAL) ---
        if ($assetlab->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Aset ini bukan milik Lab Prodi Anda.');
        }

        $labs = Lab::where('prodi_id', auth()->user()->prodi_id)->get();
        return view('admin.assetlab.edit', compact('assetlab', 'labs'));
    }

    /**
     * Memperbarui Aset Lab.
     */
    public function update(Request $request, Assetlab $assetlab)
    {
        // --- VERIFIKASI KEPEMILIKAN ASET LAMA ---
        if ($assetlab->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Aset ini bukan milik Lab Prodi Anda.');
        }
        
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'condition' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // --- VERIFIKASI KEPEMILIKAN LAB TUJUAN BARU (Jika dipindahkan antar lab di prodi yang sama) ---
        $newLab = Lab::find($request->lab_id);
        if (!$newLab || $newLab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Lab tujuan tidak valid atau bukan milik Prodi Anda.');
        }

        $assetlab->update($validated);

        return redirect()->route('admin.assetlab.index')
                         ->with('success', 'Aset Lab berhasil diperbarui.');
    }

    /**
     * Menghapus Aset Lab.
     */
    public function destroy(Assetlab $assetlab)
    {
        // --- VERIFIKASI KEPEMILIKAN ASET (KRUSIAL) ---
        if ($assetlab->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Akses Ditolak. Aset ini bukan milik Lab Prodi Anda.');
        }
        
        $assetlab->delete();

        return redirect()->route('admin.assetlab.index')
                         ->with('success', 'Aset Lab berhasil dihapus.');
    }
}