<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AaiReport extends Model
{
    use HasFactory;
    protected $table = 'aai_reports';
    protected $guarded = [];

    public function setBookingDateAttribute($value)
    {
        $this->attributes['booking_date'] = Carbon::parse($value);
    }

    public function setModificationDateAttribute($value)
    {
        $this->attributes['modification_date'] = Carbon::parse($value);
    }

    public function setDepartureDateAttribute($value)
    {
        $this->attributes['departure_date'] = Carbon::parse($value);
    }

    public function setDepartureDateUtcAttribute($value)
    {
        $this->attributes['departure_date_utc'] = Carbon::parse($value);
    }

    public function setDepartureDateLocalAttribute($value)
    {
        $this->attributes['departure_date_local'] = Carbon::parse($value);
    }

}
