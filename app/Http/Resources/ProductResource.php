<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'features' => $this->features,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'image_path' => $this->image_path,
            'image_url' => $this->image_path ? Storage::url($this->image_path) : null,
            'metadata' => $this->metadata,
            'pricing_plans' => $this->whenLoaded('pricingPlans', function () {
                return PricingPlanResource::collection($this->pricingPlans);
            }),
            'pricing_plans_count' => $this->whenCounted('pricingPlans'),
            'has_active_plans' => $this->whenLoaded('pricingPlans', function () {
                return $this->pricingPlans->where('is_active', true)->isNotEmpty();
            }),
            'lowest_price' => $this->whenLoaded('pricingPlans', function () {
                return $this->getLowestPrice();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
