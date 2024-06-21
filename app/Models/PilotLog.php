<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotLog extends Model
{
    use HasFactory; 

    protected $fillable = [
        'date',
        'aircraft_id',
        'user_id',
        'user_role',
        'fron_sector',
        'to_sector',
        'flying_type',
        'departure_time',
        'arrival_time',
        'night_time',
        'flying_type',
        'is_process',
        'log_type',
    ];
    protected $casts = [
        'passenger'=>'array'
    ];
    public function pilot()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function aircraft()
    {
        return $this->belongsTo(AirCraft::class, 'aircraft_id');
    }
}
