<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'prodi_id'
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
