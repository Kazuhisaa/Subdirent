<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenuePrediction extends Model
{
    // di ko sure kung every month to magagamit para pang dagdag sa prediction
    protected $fillable = [
        'month',
         'historical_revenue'
    ];
}
