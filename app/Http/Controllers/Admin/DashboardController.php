<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\assetlab;
use App\Models\Peminjaman; // Gunakan model Peminjaman yang baru
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userProdiId = auth()->user()->prodi_id;
        
        // Ambil ID semua lab milik prodi ini
        $labIds = Lab::where('prodi_id', $userProdiId)->pluck('id');

        // 1. Total Laboratorium
        $totalLab = $labIds->count();
        
        // 2. Total Aset
        $totalAset = assetlab::whereIn('lab_id', $labIds)->sum('quantity');

        // 3. Peminjaman Aktif (Status 'Dipinjam')
        // Cari aset yang lab-nya milik prodi ini, lalu hitung peminjaman aktif
        $peminjamanAktif = Peminjaman::where('status', 'Dipinjam')
            ->whereHas('asetLab.lab', function ($query) use ($userProdiId) {
                $query->where('prodi_id', $userProdiId);
            })->count();

        // 4. Laporan Pending (Asumsi Report memiliki relasi ke Lab atau Prodi)
        // Jika belum ada model Report, ini dijadikan placeholder.
        $laporanPending = 0; 
        
        // Mengirimkan statistik ke view
        return view('admin.dashboard', compact(
            'totalLab', 
            'totalAset', 
            'peminjamanAktif', 
            'laporanPending' 
        ));
    }
}