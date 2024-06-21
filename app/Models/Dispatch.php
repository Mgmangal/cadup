<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory; 

    protected $fillable = [
        'dispatch_from',
        'dispatch_to',
        'dates',
        'subject',
        'letter_no',
        'dispatch_reg_no',
        'types',
    ];
    
    protected $casts = [
        'stamp_tickets'=>'array'
    ];

}
