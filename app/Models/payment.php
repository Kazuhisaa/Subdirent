<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'amount',
        'payment_date',
        'status',
        'method',
        'reference',
        'for_month',
        'payment_date',
        'due_date',
        'paid_at',

    ];

    // Relationship: Payment belongs to a Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
