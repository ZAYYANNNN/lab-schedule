<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    // Tampilkan semua lab
    public function index()
    {
        $labs = Lab::all();
        return view('superadmin.labs.index', compact('labs'));
    }

    // Form tambah
    public function create()
    {
        return view('superadmin.labs.create');
    }

    // Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:labs',
        ]);

        Lab::create($request->only('name', 'kode'));

        return redirect()->route('superadmin.labs.index')
                         ->with('success', 'Lab berhasil ditambahkan.');
    }

    // Form edit
    public function edit(Lab $lab)
    {
        return view('superadmin.labs.edit', compact('lab'));
    }

    // Update data
    public function update(Request $request, Lab $lab)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:labs,kode,' . $lab->id,
        ]);

        $lab->update($request->only('name', 'kode'));

        return redirect()->route('superadmin.labs.index')
                         ->with('success', 'Lab berhasil diperbarui.');
    }

    // Hapus data
    public function destroy(Lab $lab)
    {
        $lab->delete();

        return redirect()->route('superadmin.labs.index')
                         ->with('success', 'Lab berhasil dihapus.');
    }
}
