<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* REF-PR-DB-58-START: Create Order and OrderItem models */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'pricing_plan_id',
        'quantity',
        'price',
        'subtotal',
        'name',
        'description',
    ];

    /**
     * Defines the relationship to the parent Order of this order item.
     *
     * @return BelongsTo The associated Order model.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Defines the relationship to the product associated with this order item.
     *
     * @return BelongsTo The related Product model.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Defines the relationship to the pricing plan associated with this order item.
     *
     * @return BelongsTo The related PricingPlan model.
     */
    public function pricingPlan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class);
    }
}
/* REF-PR-DB-58-END */
