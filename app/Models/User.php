<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'prodi',
        'prodi',
        'prodi_id',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedules::class, 'created_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function lab()
    {
        return $this->hasOne(Lab::class, 'admin_id');
    }

    public function hasRentalAccess()
    {
        if ($this->role === 'superadmin') {
            return true;
        }

        if ($this->role === 'admin') {
            return Lab::where('admin_id', $this->id)
                ->whereHas('type', function ($q) {
                    $q->where('slug', 'kalibrasi');
                })->exists();
        }

        return false;
    }
}
