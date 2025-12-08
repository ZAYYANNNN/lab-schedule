<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Lab extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'kode_lab',
        'lokasi',
        'prodi',
        'prodi_id',
        'kapasitas',
        'pj',
        'status',
        'foto'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function assets()
    {
        return $this->hasMany(Assetlab::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedules::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}