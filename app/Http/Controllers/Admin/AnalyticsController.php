<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use App\Services\BillingService;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Show analytics dashboard
     */
    public function __invoke(): View
    {
        $activeCount = Subscription::active()->count();
        $pendingCount = Subscription::where('status', 'pending')->count();
        $cancelledCount = Subscription::where('status', 'cancelled')->count();
        $expiredCount = Subscription::where('status', 'expired')->count();
        
        $mrr = BillingService::calculateMRR();

        // Subscriptions grouped by plan
        $subscriptionsByPlan = Subscription::active()
            ->select('plan_id', \DB::raw('count(*) as total'))
            ->groupBy('plan_id')
            ->with('plan')
            ->get();

        return view('admin.analytics', compact(
            'activeCount',
            'pendingCount',
            'cancelledCount',
            'expiredCount',
            'mrr',
            'subscriptionsByPlan'
        ));
    }
}
