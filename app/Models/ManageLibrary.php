<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageLibrary extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'manage_library';

    // The attributes that are mass assignable.
    protected $guarded = [];

    // Define the relationship to get the parent record.
    public function parent()
    {
        return $this->belongsTo(ManageLibrary::class, 'parent_id');
    }
}
