<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabStatus extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /**
     * Get all labs with this status
     */
    public function labs()
    {
        return $this->hasMany(Lab::class, 'status_id');
    }
}
