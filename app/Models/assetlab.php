<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AssetLab extends Model
{
    protected $table = 'aset_labs';
    protected $fillable = ['lab_id', 'name', 'quantity', 'condition', 'description'];

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    public function prodi()
    {
        return $this->hasOneThrough(Prodi::class, Lab::class, 'id', 'id', 'lab_id', 'prodi_id');
    }

    /**
     * Scope untuk filter Aset Lab berdasarkan prodi user yang login
     */
    public function scopeByProdi(Builder $query, $prodiId = null)
    {
        $prodiId = $prodiId ?? auth()->user()->prodi_id;
        return $query->whereHas('lab', function (Builder $q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        });
    }
}
