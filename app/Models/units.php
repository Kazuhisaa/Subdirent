<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class units extends Model
{
    //

    protected $fillable =[
     'title',
     'description',
     'bedrooms',
     'floor_area',
     'location',
      'price'

    ];
}
