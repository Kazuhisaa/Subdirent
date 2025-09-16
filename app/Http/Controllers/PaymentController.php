<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Tenant;
use App\Models\Payment;

class PaymentController extends Controller
{
  // Show tenant dashboard
  public function dashboard(Tenant $tenant)
  {
    $payments = $tenant->payments()->orderBy('created_at', 'desc')->get();
    return view('tenant.dashboard', compact('tenant', 'payments'));
  }

  // Create payment (PayMongo test)
  public function createPayment(Tenant $tenant)
  {
    $response = Http::withToken(config('services.paymongo.secret_key'))
      ->post('https://api.paymongo.com/v1/checkout_sessions', [
        'data' => [
          'attributes' => [
            'line_items' => [[
              'name' => 'Monthly Rent',
              'quantity' => 1,
              'currency' => 'PHP',
              'amount' => $tenant->monthly_rent * 100,
            ]],
            'payment_method_types' => ['card', 'gcash', 'grab_pay'],
            'success_url' => route('payment.success', $tenant->id),
            'cancel_url' => route('payment.cancel', $tenant->id),
            'description' => 'Rent payment for ' . $tenant->first_name,
            'metadata' => [
              'tenant_id' => $tenant->id
            ]
          ]
        ]
      ]);

    $checkout = $response->json();

    // For testing: if PayMongo response fails, simulate a success
    if (!isset($checkout['data']['attributes']['checkout_url'])) {
      Payment::create([
        'tenant_id' => $tenant->id,
        'amount' => $tenant->monthly_rent,
        'payment_date' => now(),
        'status' => 'paid',
        'method' => 'Test'
      ]);

      return redirect()->route('tenant.dashboard', $tenant->id)
        ->with('success', 'Test payment recorded!');
    }

    return redirect($checkout['data']['attributes']['checkout_url']);
  }

  // Success page
  public function success(Tenant $tenant)
  {
    Payment::create([
      'tenant_id' => $tenant->id,
      'amount' => $tenant->monthly_rent,
      'payment_date' => now(),
      'status' => 'paid',
      'method' => 'PayMongo'
    ]);

    return view('payment.success', compact('tenant'));
  }

  // Cancel page
  public function cancel(Tenant $tenant)
  {
    return view('payment.cancel', compact('tenant'));
  }

  // Webhook (optional for real PayMongo)
  public function webhook(Request $request)
  {
    $event = $request->input('data.type');
    if ($event === 'payment.paid') {
      $attributes = $request->input('data.attributes');
      $tenantId = $attributes['metadata']['tenant_id'] ?? null;
      if ($tenantId) {
        $tenant = Tenant::find($tenantId);
        if ($tenant) {
          Payment::create([
            'tenant_id' => $tenant->id,
            'amount' => $tenant->monthly_rent,
            'payment_date' => now(),
            'status' => 'paid',
            'method' => 'PayMongo'
          ]);
        }
      }
    }
    return response()->json(['status' => 'ok']);
  }
}
