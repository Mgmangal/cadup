<?php

namespace App\Models;

use App\Models\AirCraft;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CvrFdr extends Model
{
    use HasFactory;
    protected $table = 'cvr_fdr';
    protected $guarded = [];

    public function aircraft()
    {
        return $this->belongsTo(AirCraft::class, 'aircraft_id');
    }
    public function setReceiveDateAttribute($value)
    {
        $this->attributes['receive_date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getReceiveDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setReadOutDateAttribute($value)
    {
        $this->attributes['read_out_date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getReadOutDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setAnalyzedDateAttribute($value)
    {
        $this->attributes['analyzed_date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getAnalyzedDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
}
