<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_certificates')
        ->withPivot('certificate_type', 'is_lifetime','is_mandatory','id_current_for_flying')
        ->withTimestamps();
    }
}
