<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autopay;

class AutopayController extends Controller
{
    // Setup or enable autopay
    public function store(Request $request, $tenantId)
    {
        $data = $request->validate([
            'method' => 'required|string|in:gcash,card,bank',
            'day_of_month' => 'required|integer|min:1|max:28',
        ]);

        $autopay = Autopay::updateOrCreate(
            ['tenant_id' => $tenantId],
            [
                'method' => $data['method'],
                'day_of_month' => $data['day_of_month'],
                'active' => true,
            ]
        );

        return redirect()->back()->with('success', 'Auto-Payment has been set up successfully!');
    }

    // Disable autopay
    public function destroy($tenantId)
    {
        $autopay = Autopay::where('tenant_id', $tenantId)->first();
        if ($autopay) {
            $autopay->update(['active' => false]);
        }

        return redirect()->back()->with('success', 'Auto-Payment has been disabled.');
    }
}
