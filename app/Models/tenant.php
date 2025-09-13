<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'contact',
        'house',
        'monthly_rent',
        'lease_start',
        'lease_end',
        'image',
        'notes',
    ];
}
