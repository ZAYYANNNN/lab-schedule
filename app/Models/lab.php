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
        'type_id',
        'lokasi',
        'prodi',
        'prodi_id',
        'kapasitas',
        'kapasitas',
        'admin_id',
        'status_id',
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

    public function type()
    {
        return $this->belongsTo(LabType::class, 'type_id');
    }

    public function status()
    {
        return $this->belongsTo(LabStatus::class, 'status_id');
    }

    public function assets()
    {
        return $this->hasMany(AssetLab::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedules::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}