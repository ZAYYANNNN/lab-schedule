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
        $query = AssetLab::with('lab.prodi');

        // Admin hanya lihat aset dari lab prodi-nya
        if ($user->role === 'admin') {
            $query->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }
        // Superadmin lihat semua (tidak difilter)

        return view('assets.index', ['assets' => $query->get()]);
    }

    /**
     * CREATE: Hanya Admin (Superadmin tidak boleh)
     */
    public function create()
    {
        $this->authorizeAdminOnly();
        
        $labs = Lab::where('prodi_id', auth()->user()->prodi_id)->get();
        return view('assets.create', compact('labs'));
    }

    /**
     * STORE: Hanya Admin (Superadmin tidak boleh)
     */
    public function store(Request $request)
    {
        $this->authorizeAdminOnly();
        
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'nama' => 'required|string|max:255',
            'kode_aset' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Pastikan lab yang dipilih milik prodi admin
        $lab = Lab::findOrFail($validated['lab_id']);
        if ($lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Anda tidak bisa menambah aset ke lab prodi lain.');
        }

        AssetLab::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset berhasil ditambahkan.');
    }

    /**
     * EDIT: Hanya Admin & aset harus milik prodi-nya
     */
    public function edit(AssetLab $asset)
    {
        $this->authorizeAdminOnly();
        $this->authorizeAssetOwnership($asset);

        $labs = Lab::where('prodi_id', auth()->user()->prodi_id)->get();
        return view('assets.edit', compact('asset', 'labs'));
    }

    /**
     * UPDATE: Hanya Admin & aset harus milik prodi-nya
     */
    public function update(Request $request, AssetLab $asset)
    {
        $this->authorizeAdminOnly();
        $this->authorizeAssetOwnership($asset);

        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'nama' => 'required|string|max:255',
            'kode_aset' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Pastikan lab tujuan juga milik prodi admin
        $lab = Lab::findOrFail($validated['lab_id']);
        if ($lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Anda tidak bisa memindah aset ke lab prodi lain.');
        }

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset berhasil diupdate.');
    }

    /**
     * DESTROY: Hanya Admin & aset harus milik prodi-nya
     */
    public function destroy(AssetLab $asset)
    {
        $this->authorizeAdminOnly();
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
        if ($asset->lab && $asset->lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Anda tidak memiliki akses ke aset prodi lain.');
        }
    }
}
