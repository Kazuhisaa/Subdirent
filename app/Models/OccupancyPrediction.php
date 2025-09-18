<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OccupancyPrediction extends Model
{
    //

        protected $table = 'occupancy_prediction';
    protected $fillable = [
       'Date',
        'Year',
        'Month',
        'Active tenants',
        'Vacant Units',
        'Occupancy rate'
    ];
}
