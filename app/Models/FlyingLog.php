<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlyingLog extends Model
{
    use HasFactory; 

    protected $fillable = [
        'date',
        'aircraft_id',
        'pilot1_id',
        'pilot1_role',
        'pilot2_id',
        'pilot2_role',
        'fron_sector',
        'to_sector',
        'flying_type',
        'departure_time',
        'arrival_time',
        'night_time',
        'comment',
        'passenger',
    ];
     protected $casts = [
        'passenger'=>'array'
    ];
    public function pilot1()
    {
        return $this->belongsTo(User::class, 'pilot1_id');
    }

    public function pilot2()
    {
        return $this->belongsTo(User::class, 'pilot2_id');
    }

    public function aircraft()
    {
        return $this->belongsTo(AirCraft::class, 'aircraft_id');
    }
}
