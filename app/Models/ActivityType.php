<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'activity_type_id');
    }
}
