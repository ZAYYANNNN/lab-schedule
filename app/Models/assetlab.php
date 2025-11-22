<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assetlab extends Model
{
    protected $table = 'asset_labs';

    protected $fillable = ['lab_id', 'name', 'quantity', 'condition', 'description'];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}
