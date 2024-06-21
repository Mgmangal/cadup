<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receive extends Model
{
    use HasFactory;
    protected $table = 'receives';

    public function receiveFromUser()
    {
        return $this->belongsTo(User::class, 'receive_from', 'id');
    }

    public function receiveToUser()
    {
        return $this->belongsTo(User::class, 'receive_to', 'id');
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }

    public function getDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }

}
