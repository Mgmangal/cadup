<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveBill extends Model
{
    use HasFactory;
    protected $table = 'receive_bills';

    protected $guarded = [];

    protected $casts = [
        'expenses_type' => 'json',
        'is_landing' => 'json',
        'is_parking' => 'json',
        'is_ground_handling' => 'json'
    ];
    
    public function setDatesAttribute($value)
    {
        $this->attributes['dates'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getDatesAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
}
