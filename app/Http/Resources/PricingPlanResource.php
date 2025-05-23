<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'stripe_price_id' => $this->stripe_price_id,
            'price' => $this->price,
            'monthly_price' => $this->monthly_price,
            'yearly_price' => $this->yearly_price,
            'custom_price' => $this->custom_price,
            'billing_cycle' => $this->billing_cycle,
            'features' => $this->features,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'trial_days' => $this->trial_days,
            'metadata' => $this->metadata,
            'formatted_price' => $this->formatted_price,
            'cycle_price' => $this->cycle_price,
            'yearly_savings_percentage' => $this->when(
                $this->billing_cycle === 'yearly', 
                fn() => $this->yearlySavingsPercentage()
            ),
            'product' => new ProductResource($this->whenLoaded('product')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
