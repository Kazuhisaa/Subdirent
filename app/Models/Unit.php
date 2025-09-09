<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //

    protected $fillable =[
     'title',
     'description',
     'bedrooms',
      'bathrooms',
     'floor_area',
     'location',
      'price'

    ];
}
