<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCertificate extends Model
{
    use HasFactory;
    
    public function licenses()
    {
        return $this->hasOne(Master::class,'id','master_id');
    }
    
    public function users()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    
    public function groundTrainings()
    {
        return $this->hasMany(PilotGroundTraining::class,'training_id','master_id');
    }
}
