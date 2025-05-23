<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StorePricingPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\PricingPlan::class);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && !$this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
        
        // Set the main price based on the billing cycle
        if ($this->filled('billing_cycle')) {
            switch ($this->billing_cycle) {
                case 'monthly':
                    if ($this->filled('monthly_price') && !$this->filled('price')) {
                        $this->merge(['price' => $this->monthly_price]);
                    }
                    break;
                case 'yearly':
                    if ($this->filled('yearly_price') && !$this->filled('price')) {
                        $this->merge(['price' => $this->yearly_price]);
                    }
                    break;
                case 'custom':
                    if ($this->filled('custom_price') && !$this->filled('price')) {
                        $this->merge(['price' => $this->custom_price]);
                    }
                    break;
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:price_plans'],
            'stripe_price_id' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'monthly_price' => ['nullable', 'numeric', 'min:0'],
            'yearly_price' => ['nullable', 'numeric', 'min:0'],
            'custom_price' => ['nullable', 'numeric', 'min:0'],
            'billing_cycle' => ['required', 'string', 'in:monthly,yearly,custom'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'trial_days' => ['nullable', 'integer', 'min:0'],
            'metadata' => ['nullable', 'array'],
            'metadata.popular' => ['nullable', 'boolean'],
            'metadata.recommended' => ['nullable', 'boolean'],
            'metadata.best_value' => ['nullable', 'boolean'],
            'metadata.highlight_color' => ['nullable', 'string', 'max:50'],
            'metadata.tag' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'The product is required.',
            'product_id.exists' => 'The selected product does not exist.',
            'name.required' => 'The pricing plan name is required.',
            'name.max' => 'The pricing plan name cannot exceed 255 characters.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price cannot be negative.',
            'billing_cycle.required' => 'The billing cycle is required.',
            'billing_cycle.in' => 'The billing cycle must be monthly, yearly, or custom.',
            'features.array' => 'Features must be provided as an array.',
            'features.*.string' => 'Each feature must be a string.',
            'slug.unique' => 'This slug is already in use. Please choose a different one.',
            'trial_days.integer' => 'Trial days must be a whole number.',
            'trial_days.min' => 'Trial days cannot be negative.',
        ];
    }
}
