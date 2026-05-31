<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display user subscription details & invoices
     */
    public function index(): View
    {
        $user = auth()->user();
        $activeSubscription = $user->getActiveSubscription();
        
        $subscriptions = $user->subscriptions()
            ->with(['plan', 'invoices'])
            ->latest()
            ->paginate(10);

        return view('subscription.index', compact('activeSubscription', 'subscriptions'));
    }

    /**
     * Cancel subscription
     */
    public function cancel(Subscription $subscription): RedirectResponse
    {
        if (auth()->id() !== $subscription->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $this->subscriptionService->cancelSubscription($subscription, 'User cancelled subscription from dashboard');

        return redirect()
            ->route('subscription.index')
            ->with('success', 'Langganan Anda telah berhasil dibatalkan.');
    }
}
