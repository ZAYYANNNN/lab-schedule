<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;

class BorrowingController extends Controller
{
    /**
     * Menampilkan daftar semua Peminjaman dari seluruh Lab dan Prodi (Global View).
     */
    public function index()
    {
        // Superadmin melihat semua peminjaman secara global
        $borrowings = Borrowing::with('asset.lab.prodi', 'borrower') // Eager load relasi lengkap
                               ->latest()
                               ->paginate(20);

        return view('superadmin.borrowings.index', compact('borrowings'));
    }

    /**
     * Menampilkan detail Peminjaman.
     */
    public function show(Borrowing $borrowing)
    {
        // Tampilkan detail peminjaman secara lengkap
        $borrowing->load('asset.lab.prodi', 'borrower'); 
        return view('superadmin.borrowings.show', compact('borrowing'));
    }

    // Metode create, store, edit, update, dan destroy DIHAPUS 
    // karena Superadmin hanya memiliki hak baca (sesuai permintaan role).
}