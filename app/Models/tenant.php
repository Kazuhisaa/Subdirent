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
        'unit_id',
        'monthly_rent',
        'lease_start',
        'lease_end',
        'image',
        'notes',
    ];
    public function unit()
    {
        return $this->belongsTo(Unit::class)->withDefault([
            'title' => '—'
        ]);
    }
}
