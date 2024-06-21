<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
    public function receive()
    {
        return $this->belongsTo(Receive::class);
    }
    public function receiveBill()
    {
        return $this->belongsTo(ReceiveBill::class);
    }
}
