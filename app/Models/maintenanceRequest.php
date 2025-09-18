<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    // The table name (optional if it follows Laravel's naming convention)
    protected $table = 'maintenance_requests';

    // Which fields can be mass-assigned
    protected $fillable = [
        'tenant_id',
        'house',
        'location',
        'contact',
        'request', // description of the request
        'status',  // pending, in_progress, completed
        'image'    // optional image path
    ];

    // Relationship: A maintenance request belongs to a tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
