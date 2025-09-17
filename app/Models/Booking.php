<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //

    protected $fillable = [

        'property_id',
        'booking_date',
        'booking_time',
        'full_name',
        'email',
        'phone_number',
        'visit_type',
        'notes'

    ];


    public function property()
    {
        return $this->belongsTo(Unit::class);
    }
}
