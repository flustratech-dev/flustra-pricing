<?php

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
        return $this->status === 'active' && (!$this->ended_at || $this->ended_at > now());
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
        if (!$this->started_at) {
            return now();
        }
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
