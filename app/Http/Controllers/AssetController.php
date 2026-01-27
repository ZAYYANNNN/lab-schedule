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
            $managedLab = Lab::where('admin_id', $user->id)->first();
            if ($managedLab) {
                $labQuery->where('id', $managedLab->id);
            } else {
                $labQuery->where('prodi_id', $user->prodi_id);
            }
        }
        $labs = $labQuery->orderBy('name')->get();

        // Fetch all assets for these labs, with active borrowing counts
        $assetQuery = AssetLab::with(['lab.prodi'])->withCount([
            'borrowings as borrowed_count' => function ($q) {
                $q->whereHas('status', function ($q) {
                    $q->whereIn('slug', ['pending', 'approved']);
                });
            }
        ]);
        if ($user->role === 'admin') {
            $assetQuery->whereHas('lab', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }
        $assets = $assetQuery->get();

        // Fetch return dates for markers
        $returnDates = \App\Models\Borrowing::whereHas('status', function ($q) {
            $q->whereIn('slug', ['pending', 'approved']);
        })
            ->whereIn('lab_id', $labs->pluck('id'))
            ->selectRaw('DISTINCT return_date')
            ->pluck('return_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        return view('assets.index', compact('labs', 'assets', 'returnDates'));
    }

    /**
     * CREATE: Hanya Admin (Superadmin tidak boleh)
     */
    public function create()
    {
        $labQuery = Lab::query();
        if (auth()->user()->role === 'admin') {
            $managedLab = Lab::where('admin_id', auth()->id())->first();
            if ($managedLab) {
                $labQuery->where('id', $managedLab->id);
            } else {
                $labQuery->where('prodi_id', auth()->user()->prodi_id);
            }
        }
        $labs = $labQuery->get();
        return view('assets.create', compact('labs'));
    }

    /**
     * STORE: Hanya Admin (Superadmin tidak boleh)
     */
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah aset.');
        }

        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'nama' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'kode_aset' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'maintenance_count' => 'nullable|integer|min:0',
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
            $managedLab = Lab::where('admin_id', auth()->id())->first();
            if ($managedLab) {
                $labQuery->where('id', $managedLab->id);
            } else {
                $labQuery->where('prodi_id', auth()->user()->prodi_id);
            }
        }
        $labs = $labQuery->get();
        return view('assets.edit', compact('asset', 'labs'));
    }

    /**
     * UPDATE: Hanya Admin & aset harus milik prodi-nya
     */
    public function update(Request $request, AssetLab $asset)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah aset.');
        }

        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'nama' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'kode_aset' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'maintenance_count' => 'nullable|integer|min:0',
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
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus aset.');
        }

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
