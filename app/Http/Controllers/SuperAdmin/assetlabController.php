<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\assetlab;

class AssetLabController extends Controller
{
    /**
     * Menampilkan daftar semua Aset Lab dari seluruh Lab dan Prodi (Global View).
     */
    public function index()
    {
        // Superadmin melihat semua aset dari seluruh Lab dan Prodi
        $assets = Assetlab::with('lab.prodi') // Eager load Lab dan Prodi
                 ->latest()
                 ->paginate(20);

        return view('superadmin.asetlab.index', compact('assets'));
    }

    /**
     * Menampilkan detail Aset Lab.
     */
    public function show(Assetlab $asetlab)
    {
        return view('superadmin.asetlab.show', compact('asetlab'));
    }

    // Metode create, store, edit, update, dan destroy DIHAPUS 
    // karena Superadmin tidak memiliki hak untuk memodifikasi aset.
}