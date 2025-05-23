<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingPlan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'price_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'slug',
        'stripe_price_id',
        'price',
        'monthly_price',
        'yearly_price',
        'custom_price',
        'billing_cycle',
        'features',
        'is_featured',
        'is_active',
        'trial_days',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'product_id' => 'integer',
        'price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'custom_price' => 'decimal:2',
        'billing_cycle' => 'string',
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'trial_days' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * The attributes that should be appended to arrays.
     *
     * @var array
     */
    protected $appends = [
        'formatted_price',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Set the plan's slug.
     *
     * @param string $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $value ? str($value)->slug() : str($this->name)->slug();
    }

    /**
     * Get the product that owns the pricing plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the subscriptions for the pricing plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'price_plan_id');
    }

    /**
     * Get the orders for the pricing plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'price_plan_id');
    }

    /**
     * Get the formatted price.
     * 
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->billing_cycle === 'monthly') {
            return '$' . number_format($this->price, 2) . '/month';
        } elseif ($this->billing_cycle === 'yearly') {
            $monthlyEquivalent = $this->price / 12;
            return '$' . number_format($this->price, 2) . '/year (approx. $' . number_format($monthlyEquivalent, 2) . '/month)';
        }
        
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get the price per billing cycle.
     * 
     * @return float
     */
    public function getCyclePriceAttribute(): float
    {
        return match ($this->billing_cycle) {
            'monthly' => $this->monthly_price ?? $this->price,
            'yearly' => $this->yearly_price ?? $this->price,
            'custom' => $this->custom_price ?? $this->price,
            default => $this->price,
        };
    }

    /**
     * Scope a query to only include active plans.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured plans.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by billing cycle.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $cycle
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithBillingCycle($query, string $cycle)
    {
        return $query->where('billing_cycle', $cycle);
    }

    /**
     * Calculate the savings percentage when comparing to monthly billing.
     * 
     * @return ?float
     */
    public function yearlySavingsPercentage(): ?float
    {
        if ($this->billing_cycle !== 'yearly' || !$this->monthly_price) {
            return null;
        }

        $yearlyAsMonthly = $this->yearly_price / 12;
        $savings = $this->monthly_price - $yearlyAsMonthly;
        
        return ($savings / $this->monthly_price) * 100;
    }
} 