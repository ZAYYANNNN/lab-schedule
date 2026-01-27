<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get all labs with this type
     */
    public function labs()
    {
        return $this->hasMany(Lab::class, 'type_id');
    }
}
