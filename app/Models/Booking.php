<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //

    protected $fillable = [
        'unit_id','title','price','full_name','email','contact','date','time_slot','notes','status'
    ];

    public function unit() {
        return $this->belongsTo(Unit::class);
    }
}
