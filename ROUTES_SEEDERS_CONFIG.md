# ROUTES, SEEDERS & CONFIGURATION

---

## 1️⃣ ROUTES

### routes/web.php
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PlanController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\SubscriptionController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Plans - Public
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
Route::get('/plans/{plan}', [PlanController::class, 'show'])->name('plans.show');

// Checkout - Protected
Route::middleware('auth')->group(function () {
    Route::get('/checkout/{plan}', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'processPayment'])->name('process-payment');
    
    // User subscription dashboard
    Route::get('/dashboard/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/{subscription}/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
});

// Authentication routes (built-in with Laravel)
Auth::routes();

// Admin routes
Route::middleware(['auth', 'verified', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Plans management
    Route::resource('plans', \App\Http\Controllers\Admin\PlanAdminController::class);
    
    // Subscriptions management
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionAdminController::class, ['only' => ['index', 'show']]);
    Route::post('/subscriptions/{subscription}/upgrade', [\App\Http\Controllers\Admin\SubscriptionAdminController::class, 'upgrade'])
        ->name('subscriptions.upgrade');
    Route::post('/subscriptions/{subscription}/downgrade', [\App\Http\Controllers\Admin\SubscriptionAdminController::class, 'downgrade'])
        ->name('subscriptions.downgrade');
    Route::post('/subscriptions/{subscription}/cancel', [\App\Http\Controllers\Admin\SubscriptionAdminController::class, 'cancel'])
        ->name('subscriptions.cancel');
    Route::post('/subscriptions/{subscription}/renew', [\App\Http\Controllers\Admin\SubscriptionAdminController::class, 'renew'])
        ->name('subscriptions.renew');

    // Invoices management
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceAdminController::class, ['only' => ['index', 'show']]);
    Route::post('/invoices/{invoice}/send', [\App\Http\Controllers\Admin\InvoiceAdminController::class, 'send'])
        ->name('invoices.send');

    // Analytics
    Route::get('/analytics', \App\Http\Controllers\Admin\AnalyticsController::class)->name('analytics');
});
```

### routes/api.php (Optional - untuk frontend integration)
```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlanApiController;
use App\Http\Controllers\Api\SubscriptionApiController;

Route::middleware('api')->group(function () {
    // Public API
    Route::get('/plans', [PlanApiController::class, 'index']);
    Route::get('/plans/{plan}', [PlanApiController::class, 'show']);

    // Protected API
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user/subscription', [SubscriptionApiController::class, 'current']);
        Route::post('/subscription/upgrade', [SubscriptionApiController::class, 'upgrade']);
        Route::post('/subscription/cancel', [SubscriptionApiController::class, 'cancel']);
    });

    // Admin API
    Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::apiResource('admin/plans', \App\Http\Controllers\Api\Admin\PlanApiController::class);
        Route::apiResource('admin/subscriptions', \App\Http\Controllers\Api\Admin\SubscriptionApiController::class);
        Route::get('admin/analytics', \App\Http\Controllers\Api\Admin\AnalyticsApiController::class);
    });
});
```

---

## 2️⃣ SEEDERS

### database/seeders/PlanSeeder.php
```php
<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERSONAL PLANS
        $personalFree = Plan::create([
            'name' => 'Personal Free',
            'slug' => 'personal-free',
            'category' => 'personal',
            'tier' => 'free',
            'description' => 'Paket standar untuk pencatatan harian dasar.',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'is_popular' => false,
            'is_active' => true,
            'display_order' => 1,
        ]);

        $personalLow = Plan::create([
            'name' => 'Personal Low',
            'slug' => 'personal-low',
            'category' => 'personal',
            'tier' => 'low',
            'description' => 'Upgrade untuk manajemen anggaran lebih baik.',
            'price_monthly' => 15000,
            'price_yearly' => 180000, // 20% discount
            'is_popular' => false,
            'is_active' => true,
            'display_order' => 2,
        ]);

        $personalMid = Plan::create([
            'name' => 'Personal Mid',
            'slug' => 'personal-mid',
            'category' => 'personal',
            'tier' => 'mid',
            'description' => 'Terbaik untuk individu dengan target finansial aktif.',
            'price_monthly' => 30000,
            'price_yearly' => 360000, // 20% discount
            'is_popular' => true,
            'is_active' => true,
            'display_order' => 3,
        ]);

        $personalHigh = Plan::create([
            'name' => 'Personal High',
            'slug' => 'personal-high',
            'category' => 'personal',
            'tier' => 'high',
            'description' => 'Solusi lengkap untuk investor & profesional.',
            'price_monthly' => 50000,
            'price_yearly' => 600000, // 20% discount
            'is_popular' => false,
            'is_active' => true,
            'display_order' => 4,
        ]);

        // FAMILY PLANS
        $familyLow = Plan::create([
            'name' => 'Family Low',
            'slug' => 'family-low',
            'category' => 'family',
            'tier' => 'low',
            'description' => 'Mulai kelola keuangan keluarga bersama (Maks 5 Anggota).',
            'price_monthly' => 39000,
            'price_yearly' => 468000, // 20% discount
            'is_popular' => false,
            'is_active' => true,
            'display_order' => 5,
        ]);

        $familyMid = Plan::create([
            'name' => 'Family Mid',
            'slug' => 'family-mid',
            'category' => 'family',
            'tier' => 'mid',
            'description' => 'Untuk keluarga lebih besar dengan fitur lebih lengkap (Maks 10 Anggota).',
            'price_monthly' => 69000,
            'price_yearly' => 828000, // 20% discount
            'is_popular' => true,
            'is_active' => true,
            'display_order' => 6,
        ]);

        $familyHigh = Plan::create([
            'name' => 'Family High',
            'slug' => 'family-high',
            'category' => 'family',
            'tier' => 'high',
            'description' => 'Premium tanpa batas untuk seluruh keluarga (Anggota Unlimited).',
            'price_monthly' => 99000,
            'price_yearly' => 1188000, // 20% discount
            'is_popular' => false,
            'is_active' => true,
            'display_order' => 7,
        ]);

        // BUSINESS PLAN
        $business = Plan::create([
            'name' => 'Business',
            'slug' => 'business',
            'category' => 'business',
            'tier' => 'high',
            'description' => 'Kelola arus kas perusahaan, aset kantor, dan pembukuan awal startup Anda dengan satu platform terintegrasi.',
            'price_monthly' => 550000,
            'price_yearly' => 6600000, // 20% discount
            'is_popular' => false,
            'is_active' => true,
            'display_order' => 8,
        ]);
    }
}
```

### database/seeders/PlanFeatureSeeder.php
```php
<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class PlanFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERSONAL FREE FEATURES
        $personalFree = Plan::where('slug', 'personal-free')->first();
        if ($personalFree) {
            $this->addFeatures($personalFree, [
                ['name' => 'Paket Anda Saat Ini', 'icon' => 'bi-star'],
                ['name' => 'Pencatatan Pemasukan & Pengeluaran', 'icon' => 'bi-wallet2'],
                ['name' => 'Maks. 1 Rekening', 'icon' => 'bi-bank'],
                ['name' => 'Laporan Mingguan', 'icon' => 'bi-graph-up'],
            ]);
        }

        // PERSONAL LOW FEATURES
        $personalLow = Plan::where('slug', 'personal-low')->first();
        if ($personalLow) {
            $this->addFeatures($personalLow, [
                ['name' => 'Semua fitur paket Free', 'icon' => 'bi-check2-all'],
                ['name' => 'Manajemen Anggaran', 'icon' => 'bi-calculator'],
                ['name' => 'Laporan Bulanan', 'icon' => 'bi-file-earmark-pdf'],
                ['name' => 'Maks. 3 Rekening/Dompet', 'icon' => 'bi-wallet2'],
            ]);
        }

        // PERSONAL MID FEATURES
        $personalMid = Plan::where('slug', 'personal-mid')->first();
        if ($personalMid) {
            $this->addFeatures($personalMid, [
                ['name' => 'Semua fitur paket Low', 'icon' => 'bi-check2-all'],
                ['name' => 'Target Finansial (Goals)', 'icon' => 'bi-bullseye'],
                ['name' => 'Utang & Piutang', 'icon' => 'bi-arrow-left-right'],
                ['name' => 'Maks. 10 Rekening/Aset', 'icon' => 'bi-bank'],
                ['name' => 'Visualisasi Lanjutan', 'icon' => 'bi-graph-up-arrow'],
            ]);
        }

        // PERSONAL HIGH FEATURES
        $personalHigh = Plan::where('slug', 'personal-high')->first();
        if ($personalHigh) {
            $this->addFeatures($personalHigh, [
                ['name' => 'Semua fitur paket Medium', 'icon' => 'bi-check2-all'],
                ['name' => 'Portofolio Investasi', 'icon' => 'bi-graph-up'],
                ['name' => 'Brankas Digital + OCR', 'icon' => 'bi-safe'],
                ['name' => 'Aset Unlimited', 'icon' => 'bi-infinity'],
                ['name' => 'Analisis Wealth Net', 'icon' => 'bi-pie-chart'],
            ]);
        }

        // FAMILY LOW FEATURES
        $familyLow = Plan::where('slug', 'family-low')->first();
        if ($familyLow) {
            $this->addFeatures($familyLow, [
                ['name' => 'Fitur setara Personal High', 'icon' => 'bi-check2-all'],
                ['name' => 'Dompet Bersama (Joint Account)', 'icon' => 'bi-people'],
                ['name' => 'Pembagian Tagihan (Split Bill)', 'icon' => 'bi-divide'],
                ['name' => 'Maks. 5 Anggota', 'icon' => 'bi-people-fill'],
            ]);
        }

        // FAMILY MID FEATURES
        $familyMid = Plan::where('slug', 'family-mid')->first();
        if ($familyMid) {
            $this->addFeatures($familyMid, [
                ['name' => 'Semua fitur Family Low', 'icon' => 'bi-check2-all'],
                ['name' => 'Role Admin & Anggota', 'icon' => 'bi-shield-check'],
                ['name' => 'Manajemen Aset Keluarga', 'icon' => 'bi-house-heart'],
                ['name' => 'Maks. 10 Anggota', 'icon' => 'bi-people-fill'],
            ]);
        }

        // FAMILY HIGH FEATURES
        $familyHigh = Plan::where('slug', 'family-high')->first();
        if ($familyHigh) {
            $this->addFeatures($familyHigh, [
                ['name' => 'Semua fitur Family Mid', 'icon' => 'bi-check2-all'],
                ['name' => 'Brankas Digital Keluarga', 'icon' => 'bi-safe2'],
                ['name' => 'Laporan Wealth Generation', 'icon' => 'bi-graph-up'],
                ['name' => 'Anggota Unlimited', 'icon' => 'bi-infinity'],
            ]);
        }

        // BUSINESS FEATURES
        $business = Plan::where('slug', 'business')->first();
        if ($business) {
            $this->addFeatures($business, [
                ['name' => 'Pemisahan Akun Bisnis', 'icon' => 'bi-briefcase'],
                ['name' => 'Multi-Approval System', 'icon' => 'bi-check2-square'],
                ['name' => 'Laba Rugi Otomatis', 'icon' => 'bi-calculator'],
                ['name' => 'Ekspor Data Enterprise', 'icon' => 'bi-download'],
                ['name' => 'Laporan Siap Pajak', 'icon' => 'bi-file-text'],
                ['name' => 'SLA 99.9% GUARANTEED', 'icon' => 'bi-shield-check'],
            ]);
        }
    }

    private function addFeatures(Plan $plan, array $features): void
    {
        foreach ($features as $index => $feature) {
            PlanFeature::create([
                'plan_id' => $plan->id,
                'feature_name' => $feature['name'],
                'feature_description' => $feature['description'] ?? null,
                'icon_class' => $feature['icon'] ?? 'bi-check',
                'display_order' => $index,
                'is_included' => true,
            ]);
        }
    }
}
```

### database/seeders/DatabaseSeeder.php
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed plans and features
        $this->call([
            PlanSeeder::class,
            PlanFeatureSeeder::class,
            // UserSeeder::class (optional, untuk membuat test users)
        ]);
    }
}
```

---

## 3️⃣ MIDDLEWARE

### app/Http/Middleware/IsAdmin.php
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check jika user adalah admin
        // Anda bisa punya column 'is_admin' di users table
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        return abort(403, 'Unauthorized');
    }
}
```

Daftarkan di `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ... existing middleware
    'is_admin' => \App\Http\Middleware\IsAdmin::class,
];
```

---

## 4️⃣ REQUESTS / FORM VALIDATION

### app/Http/Requests/StorePlanRequest.php
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->is_admin ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:plans,name',
            'slug' => 'required|string|max:255|unique:plans,slug',
            'category' => 'required|in:personal,family,business',
            'tier' => 'required|in:free,low,mid,high',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*.name' => 'required|string|max:255',
            'features.*.description' => 'nullable|string',
            'features.*.icon' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama paket harus diisi',
            'name.unique' => 'Nama paket sudah ada',
            'slug.required' => 'Slug harus diisi',
            'slug.unique' => 'Slug sudah ada',
            'category.required' => 'Kategori harus dipilih',
            'price_monthly.required' => 'Harga bulanan harus diisi',
        ];
    }
}
```

### app/Http/Requests/UpdatePlanRequest.php
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->is_admin ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:plans,name,' . $this->plan->id,
            'slug' => 'required|string|max:255|unique:plans,slug,' . $this->plan->id,
            'category' => 'required|in:personal,family,business',
            'tier' => 'required|in:free,low,mid,high',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*.name' => 'required|string|max:255',
            'features.*.description' => 'nullable|string',
            'features.*.icon' => 'nullable|string',
        ];
    }
}
```

---

## 5️⃣ EVENTS & LISTENERS

### app/Events/SubscriptionCreated.php
```php
<?php

namespace App\Events;

use App\Models\Subscription;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SubscriptionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Subscription $subscription)
    {
        //
    }
}
```

### app/Listeners/SendSubscriptionEmail.php
```php
<?php

namespace App\Listeners;

use App\Events\SubscriptionCreated;
use App\Mail\SubscriptionWelcomeMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SubscriptionCreated $event): void
    {
        Mail::send(new SubscriptionWelcomeMail($event->subscription));
    }
}
```

Register di `app/Providers/EventServiceProvider.php`:
```php
protected $listen = [
    SubscriptionCreated::class => [
        SendSubscriptionEmail::class,
        LogSubscriptionActivity::class,
    ],
    SubscriptionUpgraded::class => [
        SendUpgradeConfirmationEmail::class,
    ],
];
```

---

## 6️⃣ .ENV CONFIGURATION

```env
# App
APP_NAME=Flustra
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flustra_subscription
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@flustra.com"
MAIL_FROM_NAME="${APP_NAME}"

# Payment Gateway (Midtrans)
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false

# Queue
QUEUE_CONNECTION=sync (atau 'database' untuk production)

# Logging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
```

---

## 7️⃣ SETUP COMMANDS

```bash
# 1. Create database
mysql -u root -e "CREATE DATABASE flustra_subscription CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Install dependencies
composer install

# 3. Generate app key
php artisan key:generate

# 4. Run migrations
php artisan migrate

# 5. Seed database
php artisan db:seed

# 6. Create test admin user
php artisan tinker
# Paste di tinker:
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@flustra.com',
    'password' => Hash::make('password123'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);

# 7. Start development server
php artisan serve

# 8. Start queue worker (untuk email notifications)
php artisan queue:work
```

---

Selesai! Mari review keseluruhan planning...
