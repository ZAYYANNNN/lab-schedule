<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab;

class labController extends Controller
{
    /**
     * Menampilkan daftar semua Lab yang dimiliki oleh Prodi Admin.
     */
    public function index()
    {
        // Filter Lab berdasarkan prodi Admin yang login
        // Ini memastikan Admin hanya melihat Lab di bawah tanggung jawabnya
        $labs = Lab::where('prodi_id', auth()->user()->prodi_id)
            ->orderBy('name')
            ->get();


        return view('admin.daftarlab', compact('labs'));
    }

    /**
     * Menampilkan form untuk mengedit data Lab.
     * RoleMiddleware akan memverifikasi kepemilikan Lab.
     */
    public function edit(Lab $lab)
    {
        // RoleMiddleware sudah memastikan Lab ini milik admin yang login.
        return view('admin.edit_lab', compact('lab'));
    }

    /**
     * Memperbarui data Lab.
     * RoleMiddleware akan memverifikasi kepemilikan Lab.
     */
    public function update(Request $request, Lab $lab)
    {
        // RoleMiddleware sudah memastikan Lab ini milik admin yang login.
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            // prodi tidak diizinkan diubah oleh Admin Prodi
        ]);
        
        $lab->update($validated);
        
        return redirect()->route('admin.labs.index')->with('success', 'Data Lab berhasil diperbarui.');
    }
    
    // Asumsi: Admin Prodi tidak diizinkan membuat atau menghapus Lab.
    // Hanya Superadmin yang seharusnya bisa mengatur data Lab dan Prodi.
}