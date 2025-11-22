<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lab extends Model
{
    protected $fillable = ['name', 'prodi_id', 'location', 'description'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function assets()
    {
        return $this->hasMany(LabAsset::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
