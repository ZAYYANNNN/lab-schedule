<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LabRental extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'lab_id',
        'nama_peminjam',
        'nim',
        'purpose',
        'rental_date',
        'return_date',
        'status',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}
