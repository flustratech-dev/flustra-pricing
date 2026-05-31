<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Events\SubscriptionCreated;
use App\Events\SubscriptionUpgraded;
use App\Events\SubscriptionDowngraded;
use App\Events\SubscriptionCancelled;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * Create a new subscription for user
     */
    public function createSubscription(
        Model $user,
        Plan $plan,
        string $billingCycle = 'monthly',
        ?string $externalId = null
    ): Subscription {
        return DB::transaction(function () use ($user, $plan, $billingCycle, $externalId) {
            // Cancel previous active subscription
            if ($user->hasActivePlan()) {
                $this->cancelSubscription($user->getActiveSubscription());
            }

            // Determine price based on billing cycle
            $price = $billingCycle === 'yearly' 
                ? $plan->price_yearly 
                : $plan->price_monthly;

            // Calculate end date
            $endDate = $billingCycle === 'yearly'
                ? now()->addYear()
                : now()->addMonth();

            // Create subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'billing_cycle' => $billingCycle,
                'status' => 'pending', // Will be 'active' after payment
                'price' => $price,
                'started_at' => now(),
                'ended_at' => $endDate,
                'auto_renew' => true,
                'external_subscription_id' => $externalId,
            ]);

            // Log the activity
            $this->logSubscriptionActivity(
                $subscription,
                'created',
                "New subscription created: {$plan->name}"
            );

            // Fire event
            event(new SubscriptionCreated($subscription));

            return $subscription;
        });
    }

    /**
     * Upgrade subscription to a higher plan
     */
    public function upgradeSubscription(
        Subscription $subscription,
        Plan $newPlan
    ): Subscription {
        return DB::transaction(function () use ($subscription, $newPlan) {
            $oldPlan = $subscription->plan;

            // Calculate new price
            $newPrice = $subscription->billing_cycle === 'yearly'
                ? $newPlan->price_yearly
                : $newPlan->price_monthly;

            // Update subscription
            $subscription->update([
                'plan_id' => $newPlan->id,
                'price' => $newPrice,
            ]);

            // Log activity
            $this->logSubscriptionActivity(
                $subscription,
                'upgraded',
                "Upgraded from {$oldPlan->name} to {$newPlan->name}",
                $oldPlan->id,
                $newPlan->id
            );

            // Fire event
            event(new SubscriptionUpgraded($subscription, $oldPlan));

            return $subscription;
        });
    }

    /**
     * Downgrade subscription to a lower plan
     */
    public function downgradeSubscription(
        Subscription $subscription,
        Plan $newPlan
    ): Subscription {
        return DB::transaction(function () use ($subscription, $newPlan) {
            $oldPlan = $subscription->plan;

            $newPrice = $subscription->billing_cycle === 'yearly'
                ? $newPlan->price_yearly
                : $newPlan->price_monthly;

            // Update subscription
            $subscription->update([
                'plan_id' => $newPlan->id,
                'price' => $newPrice,
            ]);

            // Log activity
            $this->logSubscriptionActivity(
                $subscription,
                'downgraded',
                "Downgraded from {$oldPlan->name} to {$newPlan->name}",
                $oldPlan->id,
                $newPlan->id
            );

            // Fire event
            event(new SubscriptionDowngraded($subscription, $oldPlan));

            return $subscription;
        });
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(
        Subscription $subscription,
        ?string $reason = null
    ): Subscription {
        return DB::transaction(function () use ($subscription, $reason) {
            $subscription->update([
                'status' => 'cancelled',
                'ended_at' => now(),
                'auto_renew' => false,
            ]);

            // Log activity
            $this->logSubscriptionActivity(
                $subscription,
                'cancelled',
                $reason ?? 'Subscription cancelled'
            );

            // Fire event
            event(new SubscriptionCancelled($subscription));

            return $subscription;
        });
    }

    /**
     * Renew subscription
     */
    public function renewSubscription(Subscription $subscription): Subscription
    {
        return DB::transaction(function () use ($subscription) {
            $newEndDate = $subscription->billing_cycle === 'yearly'
                ? $subscription->ended_at->addYear()
                : $subscription->ended_at->addMonth();

            $subscription->update([
                'status' => 'active',
                'ended_at' => $newEndDate,
            ]);

            // Log activity
            $this->logSubscriptionActivity(
                $subscription,
                'renewed',
                'Subscription renewed'
            );

            return $subscription;
        });
    }

    /**
     * Mark subscription as active (after payment)
     */
    public function activateSubscription(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'active',
        ]);

        return $subscription;
    }

    /**
     * Log subscription activity
     */
    public function logSubscriptionActivity(
        Subscription $subscription,
        string $eventType,
        string $notes,
        ?int $oldPlanId = null,
        ?int $newPlanId = null
    ): void {
        $subscription->logs()->create([
            'event_type' => $eventType,
            'old_plan_id' => $oldPlanId,
            'new_plan_id' => $newPlanId,
            'notes' => $notes,
        ]);
    }
}
