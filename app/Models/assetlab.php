<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class AssetLab extends Model
{
    use HasUuids;

    protected $table = 'aset_labs';
    public $incrementing = false;
    protected $keyType = 'string';

    // Sesuaikan dengan kolom di migration: nama, kode_aset, jumlah
    protected $fillable = ['id', 'lab_id', 'nama', 'kategori', 'kode_aset', 'jumlah', 'maintenance_count'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

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

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'asset_id');
    }
}
