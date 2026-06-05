<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BillingService
{
    /**
     * Generate invoice for subscription
     */
    public function generateInvoice(Subscription $subscription): Invoice
    {
        return DB::transaction(function () use ($subscription) {
            $invoiceNumber = $this->generateInvoiceNumber();
            
            // Calculate billing period
            $billingPeriodStart = $subscription->started_at ?? now();
            $billingPeriodEnd = $subscription->billing_cycle === 'yearly'
                ? $billingPeriodStart->clone()->addYear()
                : $billingPeriodStart->clone()->addMonth();

            $invoice = Invoice::create([
                'subscription_id' => $subscription->id,
                'invoice_number' => $invoiceNumber,
                'amount' => $subscription->price,
                'status' => 'pending',
                'billing_period_start' => $billingPeriodStart,
                'billing_period_end' => $billingPeriodEnd,
                'due_date' => now()->addDays(7),
            ]);

            return $invoice;
        });
    }

    /**
     * Mark invoice as paid
     */
    public function markInvoiceAsPaid(
        Invoice $invoice,
        ?string $externalId = null
    ): Invoice {
        $invoice->update([
            'status' => 'paid',
            'paid_amount' => $invoice->amount,
            'paid_at' => now(),
            'external_invoice_id' => $externalId,
        ]);

        // Activate subscription if pending
        if ($invoice->subscription->status === 'pending') {
            $service = new SubscriptionService();
            $service->activateSubscription($invoice->subscription);
        }

        return $invoice;
    }

    /**
     * Calculate MRR (Monthly Recurring Revenue)
     */
    public static function calculateMRR(): float
    {
        // Get all active monthly subscriptions
        $monthlyRevenue = Subscription::active()
            ->where('billing_cycle', 'monthly')
            ->sum('price');

        // Get all active yearly subscriptions and convert to monthly
        $yearlyRevenue = Subscription::active()
            ->where('billing_cycle', 'yearly')
            ->sum('price');
        
        $yearlyAsMonthly = $yearlyRevenue / 12;

        return (float) ($monthlyRevenue + $yearlyAsMonthly);
    }

    /**
     * Generate invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ym');
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->latest('created_at')
            ->first();

        if ($lastInvoice) {
            $number = intval(substr($lastInvoice->invoice_number, -5)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Check expiring subscriptions and send reminders
     */
    public function checkExpiringSubscriptions(): void
    {
        $expiringSubscriptions = Subscription::expiring(7)->get();

        foreach ($expiringSubscriptions as $subscription) {
            // Send reminder email
            // Mail::send(...);
        }
    }

    /**
     * Auto-renew subscriptions
     */
    public function autoRenewSubscriptions(): void
    {
        $subscriptions = Subscription::where('auto_renew', true)
            ->where('status', 'active')
            ->where('ended_at', '<=', now()->addHours(1))
            ->get();

        foreach ($subscriptions as $subscription) {
            $this->generateInvoice($subscription);
            // Process payment via gateway
        }
    }
}
