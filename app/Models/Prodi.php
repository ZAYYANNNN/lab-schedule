<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodis';

    protected $fillable = [
        'id',
        'name'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function users()
    {
        return $this->hasMany(User::class, 'prodi_id');
    }

    public function labs()
    {
        return $this->hasMany(Lab::class, 'prodi_id');
    }
}
