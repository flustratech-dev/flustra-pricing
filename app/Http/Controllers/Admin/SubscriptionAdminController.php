<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use App\Services\SubscriptionService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionAdminController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * List all subscriptions
     */
    public function index(Request $request): View
    {
        $query = Subscription::with('user', 'plan');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by plan
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        $subscriptions = $query->latest()->paginate(20);
        $statuses = ['active', 'cancelled', 'expired', 'pending'];
        $plans = Plan::all();

        return view('admin.subscriptions.index', compact(
            'subscriptions',
            'statuses',
            'plans'
        ));
    }

    /**
     * Show subscription details
     */
    public function show(Subscription $subscription): View
    {
        $subscription->load('user', 'plan', 'logs', 'invoices');

        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Upgrade subscription
     */
    public function upgrade(Subscription $subscription, Request $request): RedirectResponse
    {
        $newPlan = Plan::findOrFail($request->plan_id);

        if (!$subscription->user->canUpgrade($newPlan)) {
            return back()->with('error', 'Cannot upgrade to a lower or same tier plan');
        }

        $this->subscriptionService->upgradeSubscription($subscription, $newPlan);

        return redirect()
            ->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription upgraded successfully');
    }

    /**
     * Downgrade subscription
     */
    public function downgrade(Subscription $subscription, Request $request): RedirectResponse
    {
        $newPlan = Plan::findOrFail($request->plan_id);

        if (!$subscription->user->canDowngrade($newPlan)) {
            return back()->with('error', 'Cannot downgrade to a higher or same tier plan');
        }

        $this->subscriptionService->downgradeSubscription($subscription, $newPlan);

        return redirect()
            ->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription downgraded successfully');
    }

    /**
     * Cancel subscription
     */
    public function cancel(Subscription $subscription, Request $request): RedirectResponse
    {
        $reason = $request->input('reason');

        $this->subscriptionService->cancelSubscription($subscription, $reason);

        return redirect()
            ->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription cancelled successfully');
    }

    /**
     * Renew subscription
     */
    public function renew(Subscription $subscription): RedirectResponse
    {
        $this->subscriptionService->renewSubscription($subscription);

        return redirect()
            ->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription renewed successfully');
    }
}
