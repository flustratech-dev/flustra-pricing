<?php

namespace App\Traits;

use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSubscription
{
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getActiveSubscription(): ?Subscription
    {
        return $this->subscriptions()
                    ->where('status', 'active')
                    ->where(function ($query) {
                        $query->whereNull('ended_at')
                              ->orWhere('ended_at', '>', now());
                    })
                    ->latest()
                    ->first();
    }

    public function hasActivePlan($planId = null): bool
    {
        $query = $this->subscriptions()
                      ->where('status', 'active')
                      ->where(function ($query) {
                          $query->whereNull('ended_at')
                                ->orWhere('ended_at', '>', now());
                      });

        if ($planId) {
            $query->where('plan_id', $planId);
        }

        return $query->exists();
    }

    public function getCurrentPlan()
    {
        return $this->getActiveSubscription()?->plan;
    }

    public function canUpgrade(Plan $newPlan): bool
    {
        $current = $this->getActiveSubscription();
        if (!$current) return true;

        // Get tier ranking
        $tierRanking = ['free' => 0, 'low' => 1, 'mid' => 2, 'high' => 3];
        
        $currentTier = $current->plan->tier ?? 'free';
        $newTier = $newPlan->tier ?? 'free';

        return ($tierRanking[$newTier] ?? 0) > ($tierRanking[$currentTier] ?? 0);
    }

    public function canDowngrade(Plan $newPlan): bool
    {
        $current = $this->getActiveSubscription();
        if (!$current) return false;

        $tierRanking = ['free' => 0, 'low' => 1, 'mid' => 2, 'high' => 3];
        
        $currentTier = $current->plan->tier ?? 'free';
        $newTier = $newPlan->tier ?? 'free';

        return ($tierRanking[$newTier] ?? 0) < ($tierRanking[$currentTier] ?? 0);
    }
}
