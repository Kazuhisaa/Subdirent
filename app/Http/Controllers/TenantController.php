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
      'first_name'    => 'required|string|max:255',
      'middle_name'   => 'nullable|string|max:255',
      'last_name'     => 'required|string|max:255',
      'email'         => 'required|email|max:255',
      'contact'       => 'required|string|max:50',
      'house'         => 'required|string|max:255',
      'monthly_rent'  => 'required|numeric|min:0',
      'lease_start'   => 'required|date',
      'lease_end'     => 'required|date|after_or_equal:lease_start',
      'notes'         => 'nullable|string',
      'image'         => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $imageName = null;
    if ($request->hasFile('image')) {
      $imageName = time() . '.' . $request->image->extension();
      $request->image->move(public_path('uploads/tenants'), $imageName);
    }

    $tenant = Tenant::create([
      'first_name'   => strip_tags($request->first_name),
      'middle_name'  => strip_tags($request->middle_name),
      'last_name'    => strip_tags($request->last_name),
      'email'        => strip_tags($request->email),
      'contact'      => strip_tags($request->contact),
      'house'        => strip_tags($request->house),
      'monthly_rent' => $request->monthly_rent,
      'lease_start'  => $request->lease_start,
      'lease_end'    => $request->lease_end,
      'notes'        => strip_tags($request->notes),
      'image'        => $imageName, // ✅ save uploaded filename
    ]);

    return response()->json([
      'message' => 'Tenant created successfully',
      'tenant' => $tenant
    ]);
  }

  // Get all tenants
  public function index()
  {
    $tenants = Tenant::all();
    return response()->json($tenants);
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
      'house'         => 'required|string|max:255',
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
}
