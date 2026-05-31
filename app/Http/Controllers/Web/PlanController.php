<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\View\View;

class PlanController extends Controller
{
    /**
     * Show all plans
     */
    public function index(): View
    {
        $plans = Plan::active()
            ->with('features')
            ->get()
            ->groupBy('category');

        return view('plans.index', compact('plans'));
    }

    /**
     * Show plan details
     */
    public function show(Plan $plan): View
    {
        $plan->load('features');

        return view('plans.show', compact('plan'));
    }
}
