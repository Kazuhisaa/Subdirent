<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\tenant;

class MaintenanceRequestController extends Controller
{
  public function index()
  {
    $requests = MaintenanceRequest::with('tenant')->get();
    return response()->json($requests);
  }

  public function show($id)
  {
    $request = MaintenanceRequest::with('tenant')->find($id);
    if (!$request) {
      return response()->json(['message' => 'Not found'], 404);
    }
    return response()->json($request);
  }

  public function store(Request $request)
  {
    $tenantId = $request->input('tenant_id');
    $tenant = \App\Models\Tenant::with('unit')->findOrFail($tenantId);



    $validated = $request->validate([
      'request' => 'required|string',
      'image' => 'nullable|image|max:2048',
    ]);

    $validated['tenant_id'] = $tenant->id;
    $validated['house'] = $tenant->unit?->title ?? 'N/A';
    $validated['location'] = $tenant->unit?->location ?? 'N/A';
    $validated['contact'] = $tenant->contact ?? 'N/A';
    $validated['status'] = 'pending';

    if ($request->hasFile('image')) {
      $path = $request->file('image')->store('maintenance_images', 'public');
      $validated['image'] = $path;
    }

    $maintenance = \App\Models\MaintenanceRequest::create($validated);

    return response()->json($maintenance, 201);
  }


  public function updateStatus(Request $request, $id)
  {
    $maintenanceRequest = MaintenanceRequest::findOrFail($id);

    $validated = $request->validate([
      'status' => 'required|in:pending,in_progress,completed',
    ]);

    $maintenanceRequest->update($validated);

    return response()->json($maintenanceRequest);
  }



  public function destroy($id)
  {
    $maintenanceRequest = MaintenanceRequest::find($id);
    if (!$maintenanceRequest) {
      return response()->json(['message' => 'Not found'], 404);
    }

    $maintenanceRequest->delete();
    return response()->json(['message' => 'Deleted successfully']);
  }
}
