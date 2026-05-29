# PLANNING SISTEM SUBSCRIPTION PLAN - FLUSTRA
## Menggunakan Laravel PHP + MySQL + Vue.js/Blade

---

## 📋 OVERVIEW SISTEM

### 1. Struktur Plan yang Dibutuhkan
```
├── PERSONAL (3 tier)
│   ├── Personal Free (Gratis)
│   ├── Personal Low (Rp 15k/bln)
│   ├── Personal Mid (Rp 30k/bln) ⭐ PALING POPULER
│   └── Personal High (Rp 50k/bln)
│
├── FAMILY (3 tier)
│   ├── Family Low (Rp 39k/bln)
│   ├── Family Mid (Rp 69k/bln) ⭐ PALING POPULER
│   └── Family High (Rp 99k/bln)
│
└── BUSINESS (1 tier)
    └── Business Enterprise (Rp 550k/bln)
```

### 2. Core Features per Plan
Setiap plan memiliki:
- ✅ List of features/keuntungan
- ✅ Price (monthly/yearly dengan discount 20%)
- ✅ Billing cycle toggle
- ✅ Call-to-action button
- ✅ Popular badge (untuk tier paling populer)

---

## 🗄️ DATABASE STRUCTURE

### Table 1: `plans`
```sql
CREATE TABLE plans (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    category ENUM('personal', 'family', 'business'),
    tier ENUM('free', 'low', 'mid', 'high') NULL,
    description TEXT,
    price_monthly DECIMAL(10, 2),
    price_yearly DECIMAL(10, 2),
    is_popular BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Index untuk performa
CREATE INDEX idx_category ON plans(category);
CREATE INDEX idx_active_order ON plans(is_active, display_order);
```

### Table 2: `plan_features`
```sql
CREATE TABLE plan_features (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    plan_id BIGINT NOT NULL,
    feature_name VARCHAR(255) NOT NULL,
    feature_description TEXT,
    icon_class VARCHAR(100),
    display_order INT,
    is_included BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE,
    KEY idx_plan ON (plan_id)
);
```

### Table 3: `subscriptions`
```sql
CREATE TABLE subscriptions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    plan_id BIGINT NOT NULL,
    billing_cycle ENUM('monthly', 'yearly'),
    status ENUM('active', 'cancelled', 'expired', 'pending') DEFAULT 'pending',
    price DECIMAL(10, 2),
    started_at TIMESTAMP,
    ended_at TIMESTAMP NULL,
    auto_renew BOOLEAN DEFAULT TRUE,
    payment_method_id BIGINT NULL,
    external_subscription_id VARCHAR(255) NULL, -- untuk payment gateway
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES plans(id),
    KEY idx_user ON (user_id),
    KEY idx_status ON (status),
    KEY idx_ended_at ON (ended_at)
);
```

### Table 4: `subscription_logs`
```sql
CREATE TABLE subscription_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    subscription_id BIGINT NOT NULL,
    event_type VARCHAR(100), -- 'created', 'renewed', 'upgraded', 'downgraded', 'cancelled'
    old_plan_id BIGINT NULL,
    new_plan_id BIGINT NULL,
    notes TEXT,
    created_at TIMESTAMP,
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE CASCADE,
    KEY idx_subscription ON (subscription_id)
);
```

### Table 5: `invoices`
```sql
CREATE TABLE invoices (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    subscription_id BIGINT NOT NULL,
    invoice_number VARCHAR(50) UNIQUE,
    amount DECIMAL(10, 2),
    paid_amount DECIMAL(10, 2) DEFAULT 0,
    status ENUM('pending', 'paid', 'failed', 'refunded'),
    billing_period_start DATE,
    billing_period_end DATE,
    due_date DATE,
    paid_at TIMESTAMP NULL,
    external_invoice_id VARCHAR(255) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE CASCADE,
    KEY idx_subscription ON (subscription_id),
    KEY idx_status ON (status)
);
```

---

## 🏗️ LARAVEL PROJECT STRUCTURE

```
flustra/
├── app/
│   ├── Models/
│   │   ├── Plan.php
│   │   ├── PlanFeature.php
│   │   ├── Subscription.php
│   │   ├── SubscriptionLog.php
│   │   └── Invoice.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/
│   │   │   │   ├── PlanController.php (tampilkan public plans)
│   │   │   │   └── CheckoutController.php
│   │   │   │
│   │   │   └── Admin/
│   │   │       ├── PlanAdminController.php (CRUD plans)
│   │   │       ├── SubscriptionAdminController.php (manage subscriptions)
│   │   │       └── InvoiceAdminController.php
│   │   │
│   │   └── Requests/
│   │       ├── StorePlanRequest.php
│   │       └── UpdatePlanRequest.php
│   │
│   ├── Services/
│   │   ├── SubscriptionService.php (business logic)
│   │   ├── BillingService.php (handle billing)
│   │   └── PaymentGatewayService.php
│   │
│   ├── Events/
│   │   ├── SubscriptionCreated.php
│   │   ├── SubscriptionUpgraded.php
│   │   ├── SubscriptionCancelled.php
│   │   └── InvoiceGenerated.php
│   │
│   ├── Listeners/
│   │   ├── LogSubscriptionActivity.php
│   │   └── SendSubscriptionEmail.php
│   │
│   ├── Policies/
│   │   └── SubscriptionPolicy.php
│   │
│   └── Traits/
│       └── HasSubscription.php
│
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_plans_table.php
│   │   ├── 2024_01_01_000002_create_plan_features_table.php
│   │   ├── 2024_01_01_000003_create_subscriptions_table.php
│   │   ├── 2024_01_01_000004_create_subscription_logs_table.php
│   │   └── 2024_01_01_000005_create_invoices_table.php
│   │
│   └── seeders/
│       ├── PlanSeeder.php (seed data plans)
│       └── PlanFeatureSeeder.php
│
├── resources/
│   ├── views/
│   │   ├── plans/
│   │   │   ├── index.blade.php (tampilan public plans)
│   │   │   └── show.blade.php
│   │   │
│   │   ├── checkout/
│   │   │   └── show.blade.php
│   │   │
│   │   └── admin/
│   │       ├── plans/
│   │       │   ├── index.blade.php (admin panel list plans)
│   │       │   ├── create.blade.php
│   │       │   ├── edit.blade.php
│   │       │   └── form-partial.blade.php
│   │       │
│   │       ├── subscriptions/
│   │       │   ├── index.blade.php
│   │       │   ├── show.blade.php
│   │       │   └── edit.blade.php
│   │       │
│   │       └── invoices/
│   │           ├── index.blade.php
│   │           └── show.blade.php
│   │
│   └── components/
│       └── PlanCard.vue (component Vue untuk card plan)
│
├── routes/
│   ├── web.php (public routes)
│   └── admin.php (admin routes - middleware auth+admin)
│
└── tests/
    ├── Feature/
    │   ├── SubscriptionTest.php
    │   └── PlanTest.php
    └── Unit/
        └── SubscriptionServiceTest.php
```

---

## 📱 FITUR YANG DIBUTUHKAN

### A. PUBLIC WEBSITE (User-facing)
1. **Halaman Lihat Plans**
   - Filter by category (Personal/Family/Business)
   - Toggle billing cycle (Monthly/Yearly dengan 20% discount)
   - Card design sesuai design system Anda
   - Popular badge untuk tier terpilih

2. **Halaman Checkout**
   - Ringkasan plan yang dipilih
   - Form data user
   - Payment method selection
   - Invoice preview

3. **User Dashboard**
   - Current subscription status
   - Billing history
   - Upgrade/downgrade option
   - Cancel subscription

### B. ADMIN PANEL
1. **Plan Management**
   - List semua plans dengan kategori
   - CRUD (Create, Read, Update, Delete) plans
   - Manage features per plan
   - Set popular status
   - View plan statistics

2. **Subscription Management**
   - List semua subscriptions (dengan filter status)
   - View subscription detail
   - Manual upgrade/downgrade
   - Cancel subscription
   - View subscription history/logs

3. **Invoice & Billing**
   - List invoices
   - Generate invoice manual
   - View payment status
   - Send invoice email reminder

4. **Analytics Dashboard**
   - Total active subscriptions per plan
   - Monthly recurring revenue (MRR)
   - Churn rate
   - Revenue chart by plan category

---

## 🔐 SECURITY & AUTHORIZATION

```php
// Model Policy untuk authorization
// app/Policies/PlanPolicy.php
- viewAny() - admin only
- view() - public (untuk public plans)
- create() - admin only
- update() - admin only
- delete() - admin only

// Middleware routes
Route::middleware(['auth', 'verified'])->group(function () {
    // User routes
});

Route::middleware(['auth', 'verified', 'is_admin'])->prefix('admin')->group(function () {
    // Admin routes
});
```

---

## 💳 INTEGRASI PAYMENT GATEWAY (Recommendations)

Pilih salah satu atau kombinasi:
1. **Midtrans** (Indonesia - recommended untuk market lokal)
2. **Stripe** (International)
3. **PayPal**

Services untuk abstraksi:
```php
// app/Services/PaymentGatewayService.php
- processPayment($subscription)
- verifyPayment($externalId)
- handleWebhook($payload)
- refund($invoiceId)
```

---

## 📊 API ENDPOINTS (BONUS untuk frontend integration)

### Public Endpoints
```
GET  /api/plans                    // Ambil semua plans
GET  /api/plans/{slug}             // Detail plan
POST /api/checkout                 // Create order
```

### Admin Endpoints
```
GET    /api/admin/plans            // List plans
POST   /api/admin/plans            // Create plan
PUT    /api/admin/plans/{id}       // Update plan
DELETE /api/admin/plans/{id}       // Delete plan

GET    /api/admin/subscriptions    // List subscriptions
PUT    /api/admin/subscriptions/{id} // Update subscription
DELETE /api/admin/subscriptions/{id} // Cancel subscription

GET    /api/admin/invoices         // List invoices
GET    /api/admin/analytics        // Analytics data
```

---

## 📅 IMPLEMENTATION TIMELINE (Recommended)

### Phase 1: Foundation (1-2 weeks)
- [x] Database design & migrations
- [x] Models & relationships
- [x] Service layer (SubscriptionService, BillingService)
- [x] Unit tests untuk service

### Phase 2: Public Frontend (1-2 weeks)
- [x] Halaman lihat plans dengan design sesuai Flustra
- [x] Halaman checkout
- [x] User subscription dashboard
- [x] Integration payment gateway (Midtrans/Stripe)

### Phase 3: Admin Panel (2-3 weeks)
- [x] Plan CRUD admin
- [x] Subscription management
- [x] Invoice management
- [x] Analytics dashboard
- [x] Authentication & authorization

### Phase 4: Polish & Testing (1 week)
- [x] E2E testing
- [x] Performance optimization
- [x] Email notifications
- [x] Webhook handling
- [x] Documentation

---

## 🛠️ TECH STACK RECOMMENDATIONS

**Backend:**
- Laravel 10 (latest stable)
- PHP 8.2+
- MySQL 8.0
- Queue jobs untuk email & billing (Laravel Queue)
- Cache untuk performance (Redis optional)

**Frontend:**
- Blade templating (server-side)
- Bootstrap 5 / Tailwind CSS (sesuaikan dengan Flustra design)
- Alpine.js untuk interaktivitas
- Vue.js 3 (optional, untuk admin dashboard interaktif)

**Testing:**
- PHPUnit untuk unit tests
- Feature tests untuk API
- Pest (alternative testing framework yang lebih modern)

**DevOps:**
- Docker (optional, untuk development consistency)
- GitHub Actions untuk CI/CD
- Webhook handling untuk payment notifications

---

## ✅ CHECKLIST DEVELOPMENT

### Database & Models
- [ ] Create all migrations
- [ ] Define models dengan relationships
- [ ] Add seeders dengan sample data
- [ ] Test relationships

### Services & Business Logic
- [ ] SubscriptionService (create, upgrade, downgrade, cancel)
- [ ] BillingService (invoice generation, renewal)
- [ ] PaymentGatewayService (abstraction layer)
- [ ] Unit tests untuk semua service

### Controllers & Routes
- [ ] Web routes (public plans, checkout)
- [ ] Admin routes (plan CRUD, subscription management)
- [ ] API routes (optional, untuk frontend integration)
- [ ] Request validation classes

### Frontend Views
- [ ] Public plans page (responsive design)
- [ ] Checkout page
- [ ] User dashboard / subscription status
- [ ] Admin dashboard
- [ ] Admin forms (create/edit plan)

### Payment Integration
- [ ] Setup payment gateway account
- [ ] Implement payment processing
- [ ] Webhook handling
- [ ] Email notifications
- [ ] Receipt generation

### Testing & Documentation
- [ ] Unit tests
- [ ] Feature tests
- [ ] Integration tests
- [ ] API documentation (OpenAPI/Swagger)
- [ ] Setup documentation

---

## 📝 IMPORTANT NOTES

1. **Bilingual** - Pastikan support Bahasa Indonesia & English
2. **Currency** - Gunakan IDR untuk Indonesia market
3. **Timezone** - Set timezone ke Asia/Jakarta
4. **Email Queue** - Use Laravel Queue untuk email agar tidak blocking
5. **Audit Trail** - Log semua perubahan subscription untuk compliance
6. **Backup** - Implement database backup strategy
7. **PCI Compliance** - Jangan simpan payment details, gunakan payment gateway
8. **Monitoring** - Setup error monitoring (Sentry/Bugsnag)

---

## 🎯 NEXT STEPS

1. Approve planning ini
2. Setup Laravel project dengan struktur di atas
3. Buat migrations untuk database
4. Implement service layer
5. Buat public facing views
6. Implement payment gateway
7. Buat admin panel
8. Testing & refinement

Siap untuk mulai coding? 🚀
