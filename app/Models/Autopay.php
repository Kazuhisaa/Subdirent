<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autopay extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'enabled',
        'payment_method',
        'gcash_number',
        'payment_token',
        'autopay_day',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
