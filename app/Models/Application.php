<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'title',
        'unit_id',
        'price',
        'surname',
        'first_name',
        'middle_name',
        'email',
        'contact_number',
        'id_upload',
        'salary',
        'lease_start',
        'lease_duration',
        'status',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

