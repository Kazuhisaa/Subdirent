<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentController extends Controller
{

  public function createPayment(Request $request, Tenant $tenant)
  {

    Log::info('🚀 Entered createPayment for tenant ' . $tenant->id);

    $method = $request->input('payment_method');
    $forMonth = $request->input('for_month', now()->format('Y-m'));

    if (!in_array($method, ['card', 'gcash'])) {
      return redirect()->route('tenant.dashboard', $tenant->id)
        ->with('error', 'Invalid payment method for PayMongo.');
    }

    $baseUrl = config('services.ngrok.url');

    $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
      ->post('https://api.paymongo.com/v1/checkout_sessions', [
        'data' => [
          'attributes' => [
            'line_items' => [[
              'name' => "Monthly Rent - {$tenant->first_name}",
              'quantity' => 1,
              'currency' => 'PHP',
              'amount' => intval($tenant->monthly_rent * 100), // ✅ ensure integer
            ]],
            'payment_method_types' => ['card', 'gcash'],
            'success_url' => config('app.url') . route('payment.success', $tenant->id, false),
            'cancel_url' => config('app.url') . route('payment.cancel', $tenant->id, false),
            'description' => "Rent payment for {$tenant->first_name}",
            'metadata' => [
              'tenant_id' => $tenant->id,
              'for_month' => $forMonth,
            ]
          ]
        ]
      ]);

    $checkout = $response->json();

    // 🔍 log the full response
    Log::info('🔍 PayMongo Checkout Response:', $checkout);

    if (isset($checkout['data']['attributes']['checkout_url'])) {
      return redirect()->away($checkout['data']['attributes']['checkout_url']);
    }

    return back()->with(
      'error',
      'Failed to create payment session. Response: ' . json_encode($checkout)
    );
  }

  public function success(Tenant $tenant)
  {
    return redirect()->to(route('tenant.dashboard', $tenant->id, false))
      ->with('success', 'Payment successful!');
  }

  public function cancel(Tenant $tenant)
  {
    return redirect()->to(route('tenant.dashboard', $tenant->id, false))
      ->with('error', 'Payment cancelled.');
  }


  // ✅ Webhook from PayMongo
  public function webhook(Request $request)
  {
    $payload = $request->all();

    Log::info('🔔 PayMongo Webhook Raw Payload: ' . json_encode($payload));

    $eventType = $payload['data']['attributes']['type'] ?? null;
    $checkoutData = $payload['data']['attributes']['data']['attributes'] ?? [];

    // Parse metadata (priority: checkout session → payment_intent)
    $tenantId = $checkoutData['metadata']['tenant_id']
      ?? ($checkoutData['payment_intent']['attributes']['metadata']['tenant_id'] ?? null);

    $forMonth = $checkoutData['metadata']['for_month']
      ?? ($checkoutData['payment_intent']['attributes']['metadata']['for_month'] ?? now()->format('Y-m'));

    // Payment details
    $payments = $checkoutData['payments'] ?? [];
    $paymentStatus = $payments[0]['attributes']['status'] ?? null;
    $referenceId   = $payments[0]['id'] ?? null;
    $paidAt        = $payments[0]['attributes']['paid_at'] ?? now();

    Log::info("📌 Event: {$eventType}, Tenant: {$tenantId}, Month: {$forMonth}, Status: {$paymentStatus}");

    if (!$tenantId) {
      return response()->json(['status' => 'error', 'message' => 'No tenant ID'], 400);
    }

    $tenant = Tenant::find($tenantId);
    if (!$tenant) {
      return response()->json(['status' => 'error', 'message' => 'Tenant not found'], 404);
    }

    if ($eventType === 'checkout_session.payment.paid' && $paymentStatus === 'paid') {
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
          'paid_at' => Carbon::createFromTimestamp($paidAt),
          'status' => 'paid',
          'method' => 'PayMongo',
          'for_month' => $forMonth,
          'reference_id' => $referenceId,
        ]);

        Log::info("✅ Payment saved: Tenant {$tenant->id}, Month {$forMonth}");
      }
    }

    return response()->json(['status' => 'ok']);
  }

  // ✅ Tenant Dashboard + Payment Summary
  public function dashboard(Tenant $tenant)
  {
    // ✅ Kunin lahat ng bayad (na-verified na "paid")
    $payments = Payment::where('tenant_id', $tenant->id)
      ->where('status', 'paid')
      ->orderBy('for_month')
      ->get();

    // Listahan ng paid months (e.g. ["2025-09", "2025-10"])
    $paidMonths = $payments->pluck('for_month')->toArray();

    // ✅ Buong lease period months
    $leaseStart = Carbon::parse($tenant->lease_start)->startOfMonth();
    $leaseEnd   = Carbon::parse($tenant->lease_end)->startOfMonth();

    $allMonths = [];
    $current = $leaseStart->copy();
    while ($current <= $leaseEnd) {
      $allMonths[] = $current->format('Y-m'); // e.g. "2025-09"
      $current->addMonth();
    }

    // ✅ Alamin kung ano ang hindi pa nababayaran
    $unpaidMonths = array_values(array_diff($allMonths, $paidMonths));

    // Totals
    $totalPaid = $payments->sum('amount');
    $totalDue = count($unpaidMonths) * $tenant->monthly_rent;
    $outstanding = max($totalDue, 0);

    // ✅ Next unpaid month
    if (!empty($unpaidMonths)) {
      $nextMonthStr = $unpaidMonths[0]; // unang hindi bayad
      $nextMonthDate = Carbon::createFromFormat('Y-m', $nextMonthStr);
      $nextMonth = [
        'date' => $nextMonthDate->format('F Y'),
        'amount' => $tenant->monthly_rent,
        'for_month' => $nextMonthStr,
      ];
    } else {
      $nextMonth = [
        'date' => null,
        'amount' => 0,
        'status' => 'All months are paid 🎉',
      ];
    }


    return view('tenant.dashboard', compact(
      'tenant',
      'payments',    // history ng payments
      'nextMonth',   // susunod na dapat bayaran
      'totalDue',    // lahat ng hindi pa bayad
      'totalPaid',   // lahat ng nabayad na
      'outstanding'  // balance
    ));
  }

  // ✅ Admin Payments Page
  public function adminPayments(Request $request)
  {
    $month = $request->input('month', Carbon::now()->format('Y-m'));

    $tenants = Tenant::with([
      'unit',
      'payments' => function ($q) use ($month) {
        $q->where('for_month', $month);
      },
      'autopay'
    ])->get();

    $carbonMonth = Carbon::createFromFormat('Y-m', $month);
    $prevMonth = $carbonMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $carbonMonth->copy()->addMonth()->format('Y-m');

    return view('admin.payments', compact('tenants', 'month', 'prevMonth', 'nextMonth'));
  }

  // ✅ Admin Index (summary of payments by month)
  public function index(Request $request)
  {
    $month = $request->input('month', Carbon::now()->format('Y-m'));

    $tenants = Tenant::with(['payments' => function ($query) use ($month) {
      $query->where('for_month', $month);
    }])->get();

    return view('admin.payments', compact('tenants', 'month'));
  }
}
