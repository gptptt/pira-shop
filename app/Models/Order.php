<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* REF-PR-DB-58-START: Create Order and OrderItem models */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'payment_status',
        'total_amount',
        'billing_name',
        'billing_email',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_country',
        'notes',
    ];

    /**
     * Defines the relationship to the user who placed the order.
     *
     * @return BelongsTo The user associated with this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines a one-to-many relationship to the order items associated with this order.
     *
     * @return HasMany The related order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
/* REF-PR-DB-58-END */
