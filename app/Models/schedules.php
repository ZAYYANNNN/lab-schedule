<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    protected $fillable = [
        'lab_id',
        'created_by',
        'date',
        'start_time',
        'end_time',
        'activity',
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
