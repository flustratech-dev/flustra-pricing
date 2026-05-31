<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlanAdminController extends Controller
{
    /**
     * List all plans
     */
    public function index(): View
    {
        $plans = Plan::with('features')
            ->orderBy('category')
            ->orderBy('display_order')
            ->paginate(20);

        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        $categories = ['personal', 'family', 'business'];
        $tiers = ['free', 'low', 'mid', 'high'];

        return view('admin.plans.create', compact('categories', 'tiers'));
    }

    /**
     * Store plan
     */
    public function store(StorePlanRequest $request): RedirectResponse
    {
        $plan = Plan::create($request->validated());

        // Store features if provided
        if ($request->has('features')) {
            foreach ($request->features as $index => $feature) {
                PlanFeature::create([
                    'plan_id' => $plan->id,
                    'feature_name' => $feature['name'],
                    'feature_description' => $feature['description'] ?? null,
                    'icon_class' => $feature['icon'] ?? null,
                    'display_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.plans.show', $plan)
            ->with('success', 'Plan created successfully');
    }

    /**
     * Show plan details
     */
    public function show(Plan $plan): View
    {
        $plan->load('features', 'subscriptions');

        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show edit form
     */
    public function edit(Plan $plan): View
    {
        $plan->load('features');
        $categories = ['personal', 'family', 'business'];
        $tiers = ['free', 'low', 'mid', 'high'];

        return view('admin.plans.edit', compact('plan', 'categories', 'tiers'));
    }

    /**
     * Update plan
     */
    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        // Update features
        if ($request->has('features')) {
            $plan->features()->delete();
            
            foreach ($request->features as $index => $feature) {
                PlanFeature::create([
                    'plan_id' => $plan->id,
                    'feature_name' => $feature['name'],
                    'feature_description' => $feature['description'] ?? null,
                    'icon_class' => $feature['icon'] ?? null,
                    'display_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.plans.show', $plan)
            ->with('success', 'Plan updated successfully');
    }

    /**
     * Delete plan
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully');
    }
}
