<?php

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
        if ($monthlyTotal <= 0) return 0;
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
