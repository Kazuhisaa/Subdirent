<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantController extends Controller
{
  // Add new tenant
  public function store(Request $request)
  {
    $fields = $request->validate([
      'first_name'   => 'required|string|max:255',
      'middle_name'  => 'nullable|string|max:255',
      'last_name'    => 'required|string|max:255',
      'email'        => 'required|email|unique:tenants,email',
      'contact'      => 'nullable|string|max:20',
      'unit_id'      => 'required|exists:units,id',
      'monthly_rent' => 'nullable|numeric',
      'lease_start'  => 'nullable|date',
      'lease_end'    => 'nullable|date',
      'notes'        => 'nullable|string',
      'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
      $filename = time() . '.' . $request->image->extension();
      $request->image->move(public_path('uploads/tenants'), $filename);
      $fields['image'] = $filename;
    }

    $tenant = Tenant::create($fields);

    return response()->json([
      'message' => 'Tenant added successfully',
      'tenant'  => $tenant->load('unit')
    ], 201);
  }

  public function index()
  {
    $tenants = Tenant::with('unit')->get();
    return response()->json($tenants);
  }

  // Get all tenants with units
  public function allTenant()
  {
    $tenants = Tenant::all();
    return response()->json($tenants);
  }


  // Find single tenant with unit
  public function findTenant($id)
  {
    $tenant = Tenant::with('unit')->findOrFail($id);
    return response()->json($tenant);
  }


  // Show tenant by id
  public function show($id)
  {
    $tenant = Tenant::findOrFail($id);
    return response()->json($tenant);
  }

  // Update tenant
  public function update(Request $request, Tenant $tenant)
  {
    $validated = $request->validate([
      'first_name'    => 'required|string|max:255',
      'middle_name'   => 'nullable|string|max:255',
      'last_name'     => 'required|string|max:255',
      'email'         => 'required|email|max:255',
      'contact'       => 'required|string|max:50',
      'unit_id' => 'required|exists:units,id',
      'monthly_rent'  => 'required|numeric|min:0',
      'lease_start'   => 'required|date',
      'lease_end'     => 'required|date|after_or_equal:lease_start',
      'notes'         => 'nullable|string',
      'image'         => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
      $imageName = time() . '.' . $request->image->extension();
      $request->image->move(public_path('uploads/tenants'), $imageName);
      $validated['image'] = $imageName;
    }

    $tenant->update($validated);

    return response()->json([
      'message' => 'Tenant updated successfully',
      'tenant' => $tenant
    ]);
  }

  // Delete tenant
  public function destroy(Tenant $tenant)
  {
    $tenant->delete();
    return response()->json(['message' => 'Tenant deleted successfully']);
  }

  public function dashboard($id)
  {
    $tenant = Tenant::with('unit')->findOrFail($id);
    $payments = collect(); // No payments yet

    // Point to your existing Blade file
    return view('user.tenant', compact('tenant', 'payments'));
  }
}
