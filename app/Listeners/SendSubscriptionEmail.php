<?php

namespace App\Listeners;

use App\Events\SubscriptionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendSubscriptionEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(SubscriptionCreated $event): void
    {
        Log::info("Subscription Welcome Email logged for user " . $event->subscription->user->email . " on plan " . $event->subscription->plan->name);
    }
}
