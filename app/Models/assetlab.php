<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class assetlab extends Model
{
    protected $fillable = ['lab_id', 'name', 'quantity', 'condition', 'description'];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}
