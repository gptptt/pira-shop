<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFeatureResource extends JsonResource
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
            'key' => $this->key,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'display_value' => $this->display_value,
            'options' => $this->options,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_highlighted' => $this->is_highlighted,
            'is_public' => $this->is_public,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => $this->when($this->relationLoaded('product'), new ProductResource($this->product)),
        ];
    }
}
