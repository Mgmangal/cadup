<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_type',
        'data_id', 
        'from_section',
        'from_user',
        'to_section',
        'to_user',
        'description',
        'status', 
    ];

    // Optionally, define relationships here
    // Example: 
    public function fromUser() { return $this->belongsTo(User::class, 'from_user'); }
    // Example: 
    public function toUser() { return $this->belongsTo(User::class, 'to_user'); }

}
