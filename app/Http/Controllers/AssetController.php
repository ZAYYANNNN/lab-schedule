<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetLab;
use App\Models\Lab;

class AssetController extends Controller
{
    /**
     * INDEX: Superadmin lihat semua, Admin hanya lihat prodi-nya
     */
    public function index()
    {
        $user = auth()->user();

        // Fetch accessible labs
        $labQuery = \App\Models\Lab::with('prodi');
        if ($user->role === 'admin') {
            $labQuery->where('prodi_id', $user->prodi_id);
        }
        $labs = $labQuery->orderBy('name')->get();

        // Fetch all assets for these labs
        $assetQuery = AssetLab::with(['lab.prodi']);
        if ($user->role === 'admin') {
            $assetQuery->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }
        $assets = $assetQuery->get();

        return view('assets.index', compact('labs', 'assets'));
    }

    /**
     * CREATE: Hanya Admin (Superadmin tidak boleh)
     */
    public function create()
    {
        $labQuery = Lab::query();
        if (auth()->user()->role === 'admin') {
            $labQuery->where('prodi_id', auth()->user()->prodi_id);
        }
        $labs = $labQuery->get();
        return view('assets.create', compact('labs'));
    }

    /**
     * STORE: Hanya Admin (Superadmin tidak boleh)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'nama' => 'required|string|max:255',
            'kode_aset' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Pastikan lab yang dipilih milik prodi admin (jika bukan superadmin)
        if (auth()->user()->role === 'admin') {
            $lab = Lab::findOrFail($validated['lab_id']);
            if ($lab->prodi_id !== auth()->user()->prodi_id) {
                abort(403, 'Anda tidak bisa menambah aset ke lab prodi lain.');
            }
        }

        AssetLab::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset berhasil ditambahkan.');
    }

    /**
     * EDIT: Hanya Admin & aset harus milik prodi-nya
     */
    public function edit(AssetLab $asset)
    {
        $this->authorizeAssetOwnership($asset);

        $labQuery = Lab::query();
        if (auth()->user()->role === 'admin') {
            $labQuery->where('prodi_id', auth()->user()->prodi_id);
        }
        $labs = $labQuery->get();
        return view('assets.edit', compact('asset', 'labs'));
    }

    /**
     * UPDATE: Hanya Admin & aset harus milik prodi-nya
     */
    public function update(Request $request, AssetLab $asset)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'nama' => 'required|string|max:255',
            'kode_aset' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Pastikan lab tujuan juga milik prodi admin (jika bukan superadmin)
        if (auth()->user()->role === 'admin') {
            $lab = Lab::findOrFail($validated['lab_id']);
            if ($lab->prodi_id !== auth()->user()->prodi_id) {
                abort(403, 'Anda tidak bisa memindah aset ke lab prodi lain.');
            }
        }

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset berhasil diupdate.');
    }

    /**
     * DESTROY: Hanya Admin & aset harus milik prodi-nya
     */
    public function destroy(AssetLab $asset)
    {
        $this->authorizeAssetOwnership($asset);

        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset berhasil dihapus.');
    }

    /**
     * Helper: Pastikan user adalah Admin (bukan Superadmin)
     */
    private function authorizeAdminOnly()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa melakukan aksi ini.');
        }
    }

    /**
     * Helper: Pastikan aset milik prodi Admin yang login
     */
    private function authorizeAssetOwnership(AssetLab $asset)
    {
        if (auth()->user()->role === 'superadmin') {
            return;
        }

        if ($asset->lab && $asset->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Anda tidak memiliki akses ke aset prodi lain.');
        }
    }
}
