<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\SubscriptionService;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    protected SubscriptionService $subscriptionService;
    protected BillingService $billingService;

    public function __construct(SubscriptionService $subscriptionService, BillingService $billingService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->billingService = $billingService;
    }

    /**
     * Show checkout page
     */
    public function show(Plan $plan, Request $request): View
    {
        $plan->load('features');
        $billingCycle = $request->query('cycle', 'monthly');
        
        if (!in_array($billingCycle, ['monthly', 'yearly'])) {
            $billingCycle = 'monthly';
        }

        return view('checkout.show', compact('plan', 'billingCycle'));
    }

    /**
     * Get Midtrans Snap Token
     */
    public function getSnapToken(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'full_name' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $plan = Plan::findOrFail($request->plan_id);
        $billingCycle = $request->billing_cycle;

        $basePrice = $billingCycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;
        $tax = $basePrice * 0.11;
        $totalPrice = $basePrice + $tax;

        $orderId = 'FLUSTRA_' . time() . '_' . $user->id . '_' . $plan->id . '_' . $billingCycle;

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $authHeader = 'Basic ' . base64_encode($serverKey . ':');

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round($totalPrice),
            ],
            'credit_card' => [
                'secure' => true,
            ],
            'customer_details' => [
                'first_name' => $request->full_name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'PLAN-' . $plan->id,
                    'price' => (int) round($basePrice),
                    'quantity' => 1,
                    'name' => substr($plan->name . ' (' . ($billingCycle === 'yearly' ? 'Tahunan' : 'Bulanan') . ')', 0, 50),
                ],
                [
                    'id' => 'PPN-11',
                    'price' => (int) round($tax),
                    'quantity' => 1,
                    'name' => 'PPN (11%)',
                ]
            ],
            'callbacks' => [
                'finish' => $request->getSchemeAndHttpHost() . '/checkout/callback?status=success&plan_id=' . $plan->id . '&cycle=' . $billingCycle,
                'error' => $request->getSchemeAndHttpHost() . '/checkout/callback?status=error',
                'pending' => $request->getSchemeAndHttpHost() . '/checkout/callback?status=pending',
            ]
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $authHeader,
            ])
            ->withoutVerifying()
            ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $payload);

            if ($response->successful()) {
                return response()->json([
                    'snap_token' => $response->json('token'),
                    'order_id' => $orderId,
                ]);
            }

            return response()->json(['error' => 'Midtrans API error: ' . $response->body()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle callback
     */
    public function callback(Request $request): RedirectResponse
    {
        $status = $request->query('status');
        $planId = $request->query('plan_id');
        $cycle = $request->query('cycle');

        if ($status !== 'success' || !$planId) {
            return redirect()->route('dashboard')->with('error', 'Pembayaran gagal atau dibatalkan.');
        }

        $user = auth()->user();
        $plan = Plan::findOrFail($planId);

        // 1. Create subscription (starts as pending)
        $subscription = $this->subscriptionService->createSubscription($user, $plan, $cycle);

        // 2. Generate invoice
        $invoice = $this->billingService->generateInvoice($subscription);

        // 3. Mark invoice as paid
        $this->billingService->markInvoiceAsPaid($invoice, 'TX-MIDTRANS-' . time());

        return redirect()
            ->route('subscription.index')
            ->with('success', 'Pembayaran Midtrans berhasil! Anda telah resmi berlangganan paket ' . $plan->name);
    }
}
