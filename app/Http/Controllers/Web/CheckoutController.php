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
     * Process checkout/payment
     */
    public function processPayment(Request $request): RedirectResponse
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'full_name' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);

        $user = auth()->user();
        $plan = Plan::findOrFail($request->plan_id);
        $billingCycle = $request->billing_cycle;

        // 1. Create subscription (starts as pending)
        $subscription = $this->subscriptionService->createSubscription($user, $plan, $billingCycle);

        // 2. Generate invoice
        $invoice = $this->billingService->generateInvoice($subscription);

        // 3. Auto-pay invoice for demonstration/local testing convenience
        $this->billingService->markInvoiceAsPaid($invoice, 'TX-' . uniqid());

        return redirect()
            ->route('subscription.index')
            ->with('success', 'Pembayaran berhasil! Anda telah resmi berlangganan paket ' . $plan->name);
    }
}
