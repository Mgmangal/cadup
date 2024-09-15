<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotTraining extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function training()
    {
        return $this->belongsTo(Master::class, 'training_id', 'id');
    }
    public function setRenewedOnAttribute($value)
    {
        $this->attributes['renewed_on'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getRenewedOnAttribute($value)
    {
        return !empty($value)&&$value!='0000-00-00'?date('d-m-Y', strtotime($value)):'';
    }
    public function setPlannedRenewalDateAttribute($value)
    {
        $this->attributes['planned_renewal_date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getPlannedRenewalDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setExtendedDateAttribute($value)
    {
        $this->attributes['extended_date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getExtendedDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setNextDueAttribute($value)
    {
        $this->attributes['next_due'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getNextDueAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
}
