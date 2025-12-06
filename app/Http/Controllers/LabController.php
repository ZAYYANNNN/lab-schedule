<?php

namespace App\Http\Controllers;

use App\Models\Lab;
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
            ->select(['id', 'name', 'kode_lab', 'lokasi', 'prodi', 'kapasitas', 'pj', 'status', 'foto'])
            ->when($user->role === 'admin', fn($q2) => $q2->where('prodi', $user->prodi))
            ->when(
                $q,
                fn($q2) => $q2
                    ->where(function ($wh) use ($q) {
                        $wh->where('name', 'like', "%$q%")
                            ->orWhere('kode_lab', 'like', "%$q%")
                            ->orWhere('lokasi', 'like', "%$q%");
                    })
            )
            ->orderBy('name');

        $labs = $query->get();

        if ($r->ajax() || $r->has('ajax')) {
            return response()->json($labs);
        }


        return view('labs.index', compact('labs'));
    }



    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:255',
            'kode_lab' => 'required|string|max:255',
            'lokasi' => 'required|string',
            'prodi' => 'nullable|string',
            'kapasitas' => 'required|integer|min:1',
            'pj' => 'nullable|string',
            'status' => 'required|in:Tersedia,Digunakan,Maintenance',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $r->only([
            'name',
            'kode_lab',
            'lokasi',
            'prodi',
            'kapasitas',
            'pj',
            'status'
        ]);

        // Enforce prodi for admin
        if (auth()->user()->role === 'admin') {
            $data['prodi'] = auth()->user()->prodi;
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
        if (auth()->user()->role === 'admin' && $lab->prodi !== auth()->user()->prodi) {
            abort(403, 'Unauthorized action.');
        }

        $r->validate([
            'name' => 'required|string|max:255',
            'kode_lab' => 'required|string|max:255',
            'lokasi' => 'required|string',
            'prodi' => 'nullable|string',
            'kapasitas' => 'required|integer|min:1',
            'pj' => 'nullable|string',
            'status' => 'required|in:Tersedia,Digunakan,Maintenance',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $r->only([
            'name',
            'kode_lab',
            'lokasi',
            'prodi',
            'kapasitas',
            'pj',
            'status'
        ]);

        // Enforce prodi for admin (cannot change prodi)
        if (auth()->user()->role === 'admin') {
            unset($data['prodi']); // Do not allow admin to update prodi
        }

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
        if (auth()->user()->role === 'admin' && $lab->prodi !== auth()->user()->prodi) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus foto juga
        if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
            Storage::disk('public')->delete($lab->foto);
        }

        $lab->delete();

        return back()->with('success', 'Lab berhasil dihapus');
    }
}
