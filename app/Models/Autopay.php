<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autopay extends Model
{
    protected $fillable = [
        'tenant_id',
        'method',
        'day_of_month',
        'active',
    ];

    // Relation to tenant (kung meron)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
