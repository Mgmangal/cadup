<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotGroundTraining extends Model
{
    use HasFactory;
    protected $table = 'pilot_ground_trainings';
    protected $guarded = [];
    // protected $fillable = [
    //     'user_id',
    //     'training_id',
    //     'is_applicable',
    //     'renewed_on',
    //     'seat_occupied',
    //     'planned_renewal_date',
    //     'examiner',
    //     'extended_date',
    //     'day_night',
    //     'next_due',
    //     'test_on',
    //     'status',
    //     'simulator_level',
    //     'remarks',
    //     'aircroft_registration',
    //     'aircroft_type',
    //     'aircroft_model',
    //     'P1_hours',
    //     'P2_hours',
    //     'renewal_office',
    //     'place_of_test',
    //     'approach_details',
    //     'documents',
    //     'created_by',
    //     'updated_by',
    // ];

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
