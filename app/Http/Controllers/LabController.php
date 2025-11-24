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
        $q = $r->search;

        $query = Lab::select(['id','name','kode_lab','lokasi','prodi','kapasitas','pj','status','foto'])
            ->when($q, function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('kode_lab', 'like', "%{$q}%")
                    ->orWhere('lokasi', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(50); // batasi

        $labs = $query->get();

        if ($r->ajax) {
            return response()->json($labs);
        }

        return view('superadmin.labs.index', compact('labs'));
    }


    public function store(Request $r)
    {
        $r->validate([
            'name'      => 'required|string|max:255',
            'kode_lab'  => 'required|string|max:255',
            'lokasi'    => 'required|string',
            'prodi'     => 'nullable|string',
            'kapasitas' => 'required|integer|min:1',
            'pj'        => 'nullable|string',
            'status'    => 'required|in:Tersedia,Digunakan,Rusak',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $r->only([
            'name','kode_lab','lokasi','prodi','kapasitas','pj','status'
        ]);

        // UUID WAJIB DISET
        $data['id'] = Str::uuid();

        // FOTO
        if ($r->hasFile('foto')) {
            $file = $r->file('foto');
            $filename = Str::slug($r->name).'_'.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('labs', $filename, 'public');
            $data['foto'] = $path;
        }

        Lab::create($data);

        return back()->with('success', 'Lab berhasil ditambahkan');
    }

    public function update(Request $r, Lab $lab)
    {
        $r->validate([
            'name'      => 'required|string|max:255',
            'kode_lab'  => 'required|string|max:255',
            'lokasi'    => 'required|string',
            'prodi'     => 'nullable|string',
            'kapasitas' => 'required|integer|min:1',
            'pj'        => 'nullable|string',
            'status'    => 'required|in:Tersedia,Digunakan,Rusak',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $r->only([
            'name','kode_lab','lokasi','prodi','kapasitas','pj','status'
        ]);

        // UPDATE FOTO (hapus lama dulu)
        if ($r->hasFile('foto')) {

            if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
                Storage::disk('public')->delete($lab->foto);
            }

            $file = $r->file('foto');
            $filename = Str::slug($r->name).'_'.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('labs', $filename, 'public');

            $data['foto'] = $path;
        }

        $lab->update($data);

        return back()->with('success', 'Lab berhasil diupdate');
    }

    public function destroy(Lab $lab)
    {
        // Hapus foto juga
        if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
            Storage::disk('public')->delete($lab->foto);
        }

        $lab->delete();

        return back()->with('success', 'Lab berhasil dihapus');
    }
}
