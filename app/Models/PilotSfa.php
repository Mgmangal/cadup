<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotSfa extends Model
{
    use HasFactory;

    function pilot()
    {
        return $this->belongsTo(User::class, 'user_id');   
    }

    function sfaFlyingLog()
    {
        return $this->hasMany(SfaFlyingLog::class, 'pilot_sfa_id');
    }
    
}
