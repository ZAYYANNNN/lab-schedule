<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Schedules extends Model
{
    public $incrementing = false;   
    protected $keyType = 'string'; 

    protected $fillable = [
        'lab_id',
        'created_by',
        'date',
        'start_time',
        'end_time',
        'activity',
    ];

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
        return $this->belongsTo(Lab::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
