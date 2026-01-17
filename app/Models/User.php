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
        'prodi_id',
        'lab_id'
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
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    public function hasRentalAccess()
    {
        if ($this->role === 'superadmin') {
            return true;
        }

        if ($this->role === 'admin' && $this->lab_id) {
            return $this->lab && $this->lab->type === 'sewa';
        }

        return false;
    }
}
