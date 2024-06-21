<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightDocAssign extends Model
{
    use HasFactory;

    public function master()
    {
        return $this->belongsTo(Master::class,'master_id');
    }
     protected $casts = [
        'documents'=>'array',
        'flying_logs'=>'array',
    ];
}
