<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LabController extends Controller
{

    public function index(Request $r)
    {
        $user = auth()->user();
        $q = $r->search;

        $query = Lab::query()
            ->select(['id', 'name', 'kode_lab', 'lokasi', 'prodi', 'prodi_id', 'kapasitas', 'pj', 'status', 'foto'])
            ->when(
                $user->role === 'admin',
                fn($q2) =>
                $q2->where('prodi_id', $user->prodi_id)
            )
            ->when($q, function ($q2) use ($q) {
                $q2->where(function ($wh) use ($q) {
                    $wh->where('name', 'like', "%$q%")
                        ->orWhere('kode_lab', 'like', "%$q%")
                        ->orWhere('lokasi', 'like', "%$q%");
                });
            })
            ->orderBy('name');

        $labs = $query->get();

        // 🔹 AMBIL DATA PRODI DARI DATABASE
        // 🔹 AMBIL DATA PRODI DARI DATABASE (ID & NAME)
        $prodiList = Prodi::orderBy('name')
            ->select('id', 'name')
            ->get();

        if ($r->ajax() || $r->has('ajax')) {
            return response()->json($labs);
        }

        return view('labs.index', compact('labs', 'prodiList'));
    }




    public function store(Request $r)
    {
        // Validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'kode_lab' => 'required|string|max:255',
            'lokasi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'pj' => 'nullable|string',
            'pj' => 'nullable|string',
            'status' => 'required|in:Tersedia,Digunakan,Maintenance',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        // Jika superadmin, validasi prodi_id
        if (auth()->user()->role === 'superadmin') {
            $rules['prodi_id'] = 'required|exists:prodis,id';
        }

        $r->validate($rules);

        $data = $r->only([
            'name',
            'kode_lab',
            'lokasi',
            'kapasitas',
            'pj',
            'status'
        ]);

        // LOGIKA PRODI
        if (auth()->user()->role === 'admin') {
            // Admin: Paksa ke prodi sendiri
            $data['prodi_id'] = auth()->user()->prodi_id;
            $data['prodi'] = auth()->user()->prodi()->value('name'); // Ambil nama dari relasi jika ada
        } else {
            // Superadmin: Pakai inputan
            $data['prodi_id'] = $r->prodi_id;
            $data['prodi'] = Prodi::where('id', $r->prodi_id)->value('name');
        }

        // UUID WAJIB DISET
        $data['id'] = Str::uuid();

        // FOTO
        if ($r->hasFile('foto')) {
            $file = $r->file('foto');
            $filename = Str::slug($r->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('labs', $filename, 'public');
            $data['foto'] = $path;
        }

        Lab::create($data);

        return back()->with('success', 'Lab berhasil ditambahkan');
    }

    public function update(Request $r, Lab $lab)
    {
        // Check permission for admin
        if (auth()->user()->role === 'admin' && $lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Unauthorized action.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'kode_lab' => 'required|string|max:255',
            'lokasi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'pj' => 'nullable|string',
            'pj' => 'nullable|string',
            'status' => 'required|in:Tersedia,Digunakan,Maintenance',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        if (auth()->user()->role === 'superadmin') {
            $rules['prodi_id'] = 'required|exists:prodis,id';
        }

        $r->validate($rules);

        $data = $r->only([
            'name',
            'kode_lab',
            'lokasi',
            'kapasitas',
            'pj',
            'status'
        ]);

        if (auth()->user()->role === 'superadmin') {
            // Jika superadmin mengubah prodi
            if ($r->prodi_id !== $lab->prodi_id) {
                $data['prodi_id'] = $r->prodi_id;
                $data['prodi'] = Prodi::where('id', $r->prodi_id)->value('name');
            }
        }
        // Admin TIDAK BOLEH update prodi, jadi tidak ada logika else untuk set prodi_id

        // UPDATE FOTO (hapus lama dulu)
        if ($r->hasFile('foto')) {

            if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
                Storage::disk('public')->delete($lab->foto);
            }

            $file = $r->file('foto');
            $filename = Str::slug($r->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('labs', $filename, 'public');

            $data['foto'] = $path;
        }

        $lab->update($data);

        return back()->with('success', 'Lab berhasil diupdate');
    }

    public function destroy(Lab $lab)
    {
        // Check permission for admin
        if (auth()->user()->role === 'admin' && $lab->prodi_id !== auth()->user()->prodi_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi: Cek jadwal dan peminjaman
        if ($lab->schedules()->exists()) {
            return back()->with('error', 'Gagal menghapus! Lab ini masih memiliki jadwal terkait.');
        }

        if ($lab->borrowings()->exists()) {
            return back()->with('error', 'Gagal menghapus! Lab ini memiliki riwayat peminjaman.');
        }

        // Hapus foto juga
        if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
            Storage::disk('public')->delete($lab->foto);
        }

        $lab->delete();

        return back()->with('success', 'Lab berhasil dihapus');
    }
}
