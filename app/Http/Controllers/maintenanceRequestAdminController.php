<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;

class MaintenanceRequestAdminController extends Controller
{
  public function index()
  {
    $tenants = Tenant::all();
    return view('admin.maintenanceRequest', compact('tenants'));
  }
}
