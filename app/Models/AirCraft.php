<?php

namespace App\Models;

use App\Models\FlyingLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirCraft extends Model
{
    use HasFactory;

    protected $casts = [
        'pilots'=>'array'
    ];

    public function flyingLogs()
    {
        return $this->hasMany(FlyingLog::class);
    }
}
