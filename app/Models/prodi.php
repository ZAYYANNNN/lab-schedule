<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class prodi extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function labs()
    {
        return $this->hasMany(Lab::class);
    }
}
