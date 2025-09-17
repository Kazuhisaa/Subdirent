<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autopay;
use App\Models\Tenant;

class AutopayController extends Controller
{
    // Enable or update autopay for tenant
    public function storeOrUpdate(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'enabled' => 'boolean',
            'payment_method' => 'nullable|in:gcash,card',
            'gcash_number' => 'required_if:payment_method,gcash|nullable|string|max:20',
            'payment_token' => 'required_if:payment_method,card|nullable|string|max:255',
            'autopay_day' => 'required_if:enabled,1|integer|min:1|max:28',
        ]);

        $autopay = Autopay::updateOrCreate(
            ['tenant_id' => $tenant->id],
            $validated
        );

        return response()->json([
            'status' => 'success',
            'autopay' => $autopay
        ]);
    }

    // Get autopay info for a tenant
    public function show(Tenant $tenant)
    {
        $autopay = $tenant->autopay; // via relationship
        return response()->json([
            'status' => 'success',
            'autopay' => $autopay
        ]);
    }

    // Disable autopay
    public function destroy(Tenant $tenant)
    {
        $autopay = $tenant->autopay;
        if ($autopay) {
            $autopay->delete();
        }

        return response()->json(['status' => 'success']);
    }
}
