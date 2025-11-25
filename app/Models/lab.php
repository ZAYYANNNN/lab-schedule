<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Lab extends Model
{
    protected $table = 'labs';
    protected $fillable = ['name', 'prodi_id', 'location', 'description'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function assets()
    {
        return $this->hasMany(assetLab::class, 'lab_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedules::class, 'lab_id');
    }

    /**
     * Scope untuk filter Lab berdasarkan prodi user yang login
     */
    public function scopeByProdi(Builder $query, $prodiId = null)
    {
        $prodiId = $prodiId ?? auth()->user()->prodi_id;
        return $query->where('prodi_id', $prodiId);
    }
}
