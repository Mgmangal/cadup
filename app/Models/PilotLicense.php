<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotLicense extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function license()
    {
        return $this->belongsTo(Master::class, 'license_id', 'id');
    }
    public function setRenewedOnAttribute($value)
    {
        $this->attributes['renewed_on'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getRenewedOnAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }

    public function setPlannedRenDateAttribute($value)
    {
        $this->attributes['planned_ren_date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getPlannedRenDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setIssuedOnAttribute($value)
    {
        $this->attributes['issued_on'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getIssuedOnAttribute($value)
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
        $this->attributes['next_due'] = !empty($value)&&$value!=null?date('Y-m-d', strtotime($value)):null;
    }
    public function getNextDueAttribute($value)
    {
        return $value;
    }
}
