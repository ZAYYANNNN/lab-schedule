<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingStatus extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /**
     * Get all borrowings with this status
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'status_id');
    }
}
