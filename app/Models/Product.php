<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'features',
        'is_active',
        'visibility',
        'availability',
        'is_featured',
        'show_on_homepage',
        'is_highlighted',
        'publish_at',
        'unpublish_at',
        'sort_order',
        'image_path',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_homepage' => 'boolean',
        'is_highlighted' => 'boolean',
        'publish_at' => 'date',
        'unpublish_at' => 'date',
        'sort_order' => 'integer',
        'metadata' => 'array',
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
     * Set the product's slug.
     *
     * @param string $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $value ? str($value)->slug() : str($this->name)->slug();
    }

    /**
     * Get the pricing plans for the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pricingPlans(): HasMany
    {
        return $this->hasMany(PricingPlan::class);
    }

    /**
     * Get the features for the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productFeatures(): HasMany
    {
        return $this->hasMany(ProductFeature::class);
    }

    /**
     * Scope a query to only include active products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to only include public products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }
    
    /**
     * Scope a query to only include private products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrivate($query)
    {
        return $query->where('visibility', 'private');
    }
    
    /**
     * Scope a query to only include restricted products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRestricted($query)
    {
        return $query->where('visibility', 'restricted');
    }
    
    /**
     * Scope a query to only include featured products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Scope a query to only include products shown on homepage.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }
    
    /**
     * Scope a query to only include highlighted products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlighted', true);
    }
    
    /**
     * Scope a query to only include available products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability', 'available');
    }
    
    /**
     * Scope a query to only include coming soon products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComingSoon($query)
    {
        return $query->where('availability', 'coming_soon');
    }
    
    /**
     * Scope a query to only include discontinued products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDiscontinued($query)
    {
        return $query->where('availability', 'discontinued');
    }
    
    /**
     * Scope a query to only include currently published products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        $now = Carbon::now();
        
        return $query->where(function ($query) use ($now) {
            $query->where(function ($q) use ($now) {
                $q->whereNull('publish_at')
                  ->orWhere('publish_at', '<=', $now);
            })->where(function ($q) use ($now) {
                $q->whereNull('unpublish_at')
                  ->orWhere('unpublish_at', '>', $now);
            });
        });
    }
    
    /**
     * Scope a query to only include products that are visible to the public.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisibleToPublic($query)
    {
        return $query->active()
                    ->public()
                    ->available()
                    ->published();
    }

    /**
     * Scope a query to order products by their sort order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if the product has any active pricing plans.
     *
     * @return bool
     */
    public function hasActivePlans()
    {
        return $this->pricingPlans()->where('is_active', true)->exists();
    }

    /**
     * Get the lowest price among all active pricing plans for this product.
     *
     * @return float|null
     */
    public function getLowestPrice()
    {
        $plan = $this->pricingPlans()
            ->where('is_active', true)
            ->orderBy('monthly_price')
            ->first();
            
        return $plan ? $plan->monthly_price : null;
    }

    /**
     * Get all active features for the product.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveFeatures()
    {
        return $this->productFeatures()->active()->ordered()->get();
    }

    /**
     * Get all public features for the product.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPublicFeatures()
    {
        return $this->productFeatures()->public()->active()->ordered()->get();
    }

    /**
     * Get all highlighted features for the product.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHighlightedFeatures()
    {
        return $this->productFeatures()->highlighted()->active()->ordered()->get();
    }

    /**
     * Get a specific feature by its key.
     *
     * @param string $key
     * @return \App\Models\ProductFeature|null
     */
    public function getFeatureByKey(string $key)
    {
        return $this->productFeatures()->where('key', $key)->first();
    }
    
    /**
     * Check if the product is currently published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        $now = Carbon::now();
        
        if ($this->publish_at && $this->publish_at->isAfter($now)) {
            return false;
        }
        
        if ($this->unpublish_at && $this->unpublish_at->isBefore($now)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if the product is visible to the public.
     *
     * @return bool
     */
    public function isVisibleToPublic(): bool
    {
        return $this->is_active &&
               $this->visibility === 'public' &&
               $this->availability === 'available' &&
               $this->isPublished();
    }
    
    /**
     * Check if the product is coming soon.
     *
     * @return bool
     */
    public function isComingSoon(): bool
    {
        return $this->availability === 'coming_soon';
    }
    
    /**
     * Check if the product is discontinued.
     *
     * @return bool
     */
    public function isDiscontinued(): bool
    {
        return $this->availability === 'discontinued';
    }
} 