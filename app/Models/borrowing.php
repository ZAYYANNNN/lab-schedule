<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;
    
    // Nama tabel di database, default-nya adalah 'borrowings'
    protected $table = 'borrowings';

    // Kolom-kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'asset_lab_id',
        'user_id',
        'quantity',
        'borrow_date',
        'return_date', // Tanggal rencana pengembalian
        'actual_return_date', // Tanggal aktual pengembalian
        'status', // Contoh: 'Dipinjam', 'Dikembalikan', 'Terlambat', 'Dibatalkan'
        'notes',
    ];

    // Kolom yang harus di-cast ke tipe data tertentu
    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    /**
     * Relasi: Sebuah peminjaman adalah milik satu AsetLab.
     */
    public function asset()
    {
        return $this->belongsTo(assetlab::class, 'asset_lab_id');
    }

    /**
     * Relasi: Sebuah peminjaman dilakukan oleh satu User (peminjam/mahasiswa).
     */
    public function borrower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}