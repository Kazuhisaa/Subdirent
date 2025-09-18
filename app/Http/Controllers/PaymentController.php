<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Tenant;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

      // Determine next unpaid month
      $payments = $tenant->payments()->pluck('for_month')->toArray();
      $nextMonth = now()->format('Y-m');

      $start = new \DateTime($tenant->lease_start);
      $end = new \DateTime($tenant->lease_end);

      while ($start <= $end) {
          $monthKey = $start->format('Y-m');
          if (!in_array($monthKey, $payments)) {
              $nextMonth = $monthKey;
              break;
          }
          $start->modify('+1 month');
      }

      $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
          ->post('https://api.paymongo.com/v1/checkout_sessions', [
              'data' => [
                  'attributes' => [
                      'line_items' => [[
                          'name' => 'Monthly Rent',
                          'quantity' => 1,
                          'currency' => 'PHP',
                          'amount' => $tenant->monthly_rent * 100, // convert to centavos
                      ]],
                      'payment_method_types' => ['card', 'gcash'],
                      'success_url' => route('payment.success', $tenant->id),
                      'cancel_url' => route('payment.cancel', $tenant->id),
                      'description' => 'Rent payment for ' . $tenant->first_name,
                      'metadata' => [
                          'tenant_id' => $tenant->id,
                          'for_month' => $nextMonth
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

      public function webhook(Request $request)
  {
      Log::info('PayMongo webhook received', $request->all());

      $eventType = $request->input('data.type');
      $attributes = $request->input('data.attributes', []);
      $metadata = $attributes['metadata'] ?? [];

      $tenantId = $metadata['tenant_id'] ?? null;
      $forMonth = $metadata['for_month'] ?? now()->format('Y-m');
      $status = $attributes['status'] ?? null;

      Log::info('Webhook attributes', $attributes);
      Log::info('Metadata', $metadata);

      if (!$tenantId) {
          return response()->json(['status' => 'error', 'message' => 'No tenant ID in metadata'], 400);
      }

      $tenant = Tenant::find($tenantId);
      if (!$tenant) {
          return response()->json(['status' => 'error', 'message' => 'Tenant not found'], 404);
      }

      if ($status === 'paid') {
          $exists = Payment::where('tenant_id', $tenantId)
              ->where('for_month', $forMonth)
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
                  'for_month' => $forMonth,
              ]);
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
    public function adminPayments(Request $request)
    {
        // Month parameter, default to current
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        // Get tenants with related payments for selected month
        $tenants = Tenant::with(['unit', 'payments' => function($q) use ($month) {
            $q->where('for_month', $month);
        }, 'autopay'])->get();

        $carbonMonth = Carbon::createFromFormat('Y-m', $month);
        $prevMonth = $carbonMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $carbonMonth->copy()->addMonth()->format('Y-m');

        return view('admin.payments', compact('tenants', 'month', 'prevMonth', 'nextMonth'));
    }
    public function index(Request $request)
    {
        // Default to current month
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        // Get all tenants with their payments for the given month
        $tenants = Tenant::with(['payments' => function ($query) use ($month) {
            $query->where('for_month', $month);
        }])->get();

        return view('admin.payments', compact('tenants', 'month'));

    }
}
