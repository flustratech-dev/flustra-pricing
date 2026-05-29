# IMPLEMENTASI DETAIL - SUBSCRIPTION SYSTEM FLUSTRA
## Code Examples & Architecture Details

---

## 1️⃣ MODELS & RELATIONSHIPS

### Plan Model
```php
// app/Models/Plan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category', // personal, family, business
        'tier', // free, low, mid, high
        'description',
        'price_monthly',
        'price_yearly',
        'is_popular',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class)
                    ->orderBy('display_order');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    // Methods
    public function getYearlyDiscountPercentage()
    {
        $monthlyTotal = $this->price_monthly * 12;
        $discount = (($monthlyTotal - $this->price_yearly) / $monthlyTotal) * 100;
        return round($discount);
    }

    public function getFormattedPrice($billingCycle = 'monthly')
    {
        $price = $billingCycle === 'yearly' 
            ? $this->price_yearly 
            : $this->price_monthly;
        
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    public function isFree(): bool
    {
        return $this->price_monthly == 0 && $this->price_yearly == 0;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
```

### PlanFeature Model
```php
// app/Models/PlanFeature.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeature extends Model
{
    protected $fillable = [
        'plan_id',
        'feature_name',
        'feature_description',
        'icon_class',
        'display_order',
        'is_included',
    ];

    protected $casts = [
        'is_included' => 'boolean',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
```

### Subscription Model
```php
// app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'billing_cycle',
        'status',
        'price',
        'started_at',
        'ended_at',
        'auto_renew',
        'payment_method_id',
        'external_subscription_id',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SubscriptionLog::class)
                    ->orderByDesc('created_at');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpiring($query, $days = 7)
    {
        return $query->active()
                    ->whereDate('ended_at', '<=', Carbon::now()->addDays($days));
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ended_at > now();
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->ended_at && $this->ended_at < now());
    }

    public function getNextBillingDate(): Carbon
    {
        if ($this->billing_cycle === 'yearly') {
            return $this->started_at->addYear();
        }
        return $this->started_at->addMonth();
    }

    public function getFormattedPrice()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getStatusBadgeColor()
    {
        return match ($this->status) {
            'active' => 'success',
            'cancelled' => 'danger',
            'expired' => 'secondary',
            'pending' => 'warning',
            default => 'info',
        };
    }
}
```

### User Trait
```php
// app/Traits/HasSubscription.php

namespace App\Traits;

use App\Models\Subscription;
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
                    ->where('ended_at', '>', now())
                    ->latest()
                    ->first();
    }

    public function hasActivePlan($planId = null): bool
    {
        $query = $this->subscriptions()
                      ->where('status', 'active')
                      ->where('ended_at', '>', now());

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
        
        return $tierRanking[$newPlan->tier] > $tierRanking[$current->plan->tier];
    }

    public function canDowngrade(Plan $newPlan): bool
    {
        $current = $this->getActiveSubscription();
        if (!$current) return false;

        $tierRanking = ['free' => 0, 'low' => 1, 'mid' => 2, 'high' => 3];
        
        return $tierRanking[$newPlan->tier] < $tierRanking[$current->plan->tier];
    }
}
```

Update User Model:
```php
// app/Models/User.php

use App\Traits\HasSubscription;

class User extends Authenticatable
{
    use HasSubscription; // Add this
    
    // ... rest of the User model
}
```

---

## 2️⃣ SERVICES (Business Logic)

### SubscriptionService
```php
// app/Services/SubscriptionService.php

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

            // Calculate prorated amount (optional - depends on business logic)
            // For now, just calculate new price
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
```

### BillingService
```php
// app/Services/BillingService.php

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
            $billingPeriodStart = $subscription->started_at;
            $billingPeriodEnd = $subscription->billing_cycle === 'yearly'
                ? $subscription->started_at->clone()->addYear()
                : $subscription->started_at->clone()->addMonth();

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
    public function calculateMRR(): float
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
        $expiringSubscriptions = Subscription::expiring(days: 7)->get();

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
```

---

## 3️⃣ CONTROLLERS

### PlanController (Public)
```php
// app/Http/Controllers/Web/PlanController.php

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
```

### PlanAdminController
```php
// app/Http/Controllers/Admin/PlanAdminController.php

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
```

### SubscriptionAdminController
```php
// app/Http/Controllers/Admin/SubscriptionAdminController.php

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
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by plan
        if ($request->has('plan_id')) {
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
```

---

## 4️⃣ MIGRATIONS

### Plans Table
```php
// database/migrations/2024_01_01_000001_create_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', ['personal', 'family', 'business']);
            $table->enum('tier', ['free', 'low', 'mid', 'high'])->nullable();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_yearly', 10, 2);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('category');
            $table->index(['is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
```

### Plan Features Table
```php
// database/migrations/2024_01_01_000002_create_plan_features_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->string('feature_name');
            $table->text('feature_description')->nullable();
            $table->string('icon_class')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_included')->default(true);
            $table->timestamps();

            $table->index('plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
```

### Subscriptions Table
```php
// database/migrations/2024_01_01_000003_create_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained();
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->decimal('price', 10, 2);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_subscription_id')->nullable()->unique();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index(['ended_at', 'auto_renew']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
```

---

Lanjutan di file berikutnya...
