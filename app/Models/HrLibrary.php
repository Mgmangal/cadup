<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrLibrary extends Model
{
    use HasFactory;

    protected $table = 'hr_library';

    // The attributes that are mass assignable.
    protected $guarded = [];

    public function resourceType()
    {
        return $this->belongsTo(Master::class, 'resource_type', 'id');
    }
}
