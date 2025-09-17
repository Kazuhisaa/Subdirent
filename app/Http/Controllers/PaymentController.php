<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Tenant;
use App\Models\Payment;

class PaymentController extends Controller
{
  // Create PayMongo Checkout (Card / GCash)
  public function createPayment(Request $request, Tenant $tenant)
  {
    $method = $request->input('payment_method');

    if (!in_array($method, ['card', 'gcash'])) {
      return redirect()->route('tenant.dashboard', $tenant->id)
        ->with('error', 'Invalid payment method for PayMongo.');
    }
    $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
      ->post('https://api.paymongo.com/v1/checkout_sessions', [
        'data' => [
          'attributes' => [
            'line_items' => [[
              'name' => 'Monthly Rent',
              'quantity' => 1,
              'currency' => 'PHP',
              'amount' => $tenant->monthly_rent * 100,
            ]],
            'payment_method_types' => ['card', 'gcash'],
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

    if (isset($checkout['data']['attributes']['checkout_url'])) {
      return redirect($checkout['data']['attributes']['checkout_url']);
    }

    return redirect()->route('tenant.dashboard', $tenant->id)
      ->with('error', 'Failed to create PayMongo checkout.');
  }

  // Success page
  public function success(Tenant $tenant)
  {
    return view('tenant.success', compact('tenant'));
  }

  public function cancel(Tenant $tenant)
  {
    return view('tenant.cancel', compact('tenant')); // kung cancel mo rin nasa tenant folder
  }

  // Webhook (real PayMongo payment confirmation)
  public function webhook(Request $request)
  {
    $event = $request->input('data.type');

    if ($event === 'payment.paid') {
      $attributes = $request->input('data.attributes');
      $tenantId = $attributes['metadata']['tenant_id'] ?? null;

      if ($tenantId) {
        $tenant = Tenant::find($tenantId);

        if ($tenant) {
          // Check if payment already recorded for this for_month
          $payments = $tenant->payments()->orderBy('for_month', 'desc')->get();

          // Determine next month to record
          $nextMonth = null;
          if ($tenant->lease_start && $tenant->lease_end) {
            $start = new \DateTime($tenant->lease_start);
            $start->modify('first day of this month');

            $end = new \DateTime($tenant->lease_end);
            $end->modify('first day of this month');

            while ($start <= $end) {
              $monthKey = $start->format('Y-m');

              if (!$payments->firstWhere('for_month', $monthKey)) {
                $nextMonth = $monthKey;
                break;
              }

              $start->modify('+1 month');
            }
          }

          // Fallback: kung wala, gamitin current month
          if (!$nextMonth) {
            $nextMonth = (new \DateTime())->format('Y-m');
          }

          // Record payment only if not exists for that month
          $exists = Payment::where('tenant_id', $tenantId)
            ->where('for_month', $nextMonth)
            ->where('method', 'PayMongo')
            ->where('status', 'paid')
            ->exists();

          if (!$exists) {
            Payment::create([
              'tenant_id' => $tenant->id,
              'amount' => $tenant->monthly_rent,
              'payment_date' => now(),
              'status' => 'paid',
              'method' => 'PayMongo',
              'for_month' => $nextMonth,
            ]);
          }
        }
      }
    }

    return response()->json(['status' => 'ok']);
  }

  // Dashboard + payment summary
  public function dashboard(Tenant $tenant)
  {
    $payments = $tenant->payments()->orderBy('created_at', 'desc')->get();

    $nextMonth = null;
    if ($tenant->lease_start && $tenant->lease_end) {
      $start = new \DateTime($tenant->lease_start);
      $start->modify('first day of this month');

      $end = new \DateTime($tenant->lease_end);
      $end->modify('first day of this month');

      while ($start <= $end) {
        $monthKey = $start->format('Y-m');

        if (!$nextMonth && !$payments->firstWhere('for_month', $monthKey)) {
          $nextMonth = [
            'month' => $start->format('F Y'),
            'date'  => $start->format('F d, Y'),
            'amount' => $tenant->monthly_rent,
            'for_month' => $monthKey,
          ];
        }

        $start->modify('+1 month');
      }
    }

    if (!$nextMonth) {
      $start = new \DateTime($tenant->lease_start);
      $start->modify('+1 month');

      $nextMonth = [
        'month' => $start->format('F Y'),
        'date'  => $start->format('F d, Y'),
        'amount' => $tenant->monthly_rent,
        'for_month' => $start->format('Y-m'),
      ];
    }

    $totalDue = $nextMonth['amount'] ?? $tenant->monthly_rent;
    $totalPaid = $payments->sum('amount');
    $outstanding = max($totalDue - $totalPaid, 0);

    return view('tenant.dashboard', compact(
      'tenant',
      'payments',
      'nextMonth',
      'totalDue',
      'totalPaid',
      'outstanding'
    ));
  }
}
