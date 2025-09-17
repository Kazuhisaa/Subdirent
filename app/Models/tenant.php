<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

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
        'image'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
