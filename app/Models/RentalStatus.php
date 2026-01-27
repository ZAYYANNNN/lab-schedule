<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalStatus extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /**
     * Get all rentals with this status
     */
    public function rentals()
    {
        return $this->hasMany(LabRental::class, 'status_id');
    }
}
