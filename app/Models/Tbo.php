<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbo extends Model
{
    use HasFactory;

    protected $table = 'tbos';

    protected $guarded = ['id'];
}
