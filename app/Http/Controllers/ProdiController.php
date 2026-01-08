<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;

class ProdiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'names' => 'required|array|min:1',
            'names.*' => 'nullable|string|max:255',
        ]);

        $count = 0;
        foreach ($request->names as $name) {
            if (!empty(trim($name))) {
                Prodi::create(['name' => $name]);
                $count++;
            }
        }

        if ($count > 0) {
            return back()->with('success', $count . ' Prodi berhasil dibuat');
        }

        return back()->with('error', 'Tidak ada prodi yang dibuat');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prodi $prodi)
    {
        // Check if prodi has users or labs
        if ($prodi->users()->count() > 0 || $prodi->labs()->count() > 0) {
            return back()->with('error', 'Prodi tidak bisa dihapus karena masih memiliki data terkait (user atau lab)');
        }

        $prodi->delete();
        return back()->with('success', 'Prodi berhasil dihapus');
    }
}
