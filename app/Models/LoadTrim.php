<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadTrim extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function air_craft()
    {
        return $this->belongsTo(AirCraft::class,'aircraft','id');
    }
}
