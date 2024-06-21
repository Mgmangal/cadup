<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalFlyingLog extends Model
{
    use HasFactory; 

    protected $fillable = [
        'date',
        'aircraft_id',
        'aircraft_type',
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
    ];

    public function pilot1()
    {
        return $this->belongsTo(User::class, 'pilot1_id');
    }
}
