<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProductFeatureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('product_feature'));
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && !$this->filled('key')) {
            $this->merge([
                'key' => Str::slug($this->name),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productFeature = $this->route('product_feature');
        
        return [
            'product_id' => ['sometimes', 'exists:products,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'key' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-_]+$/',
                Rule::unique('product_features', 'key')
                    ->ignore($productFeature)
                    ->where(function ($query) use ($productFeature) {
                        return $query->where('product_id', $this->product_id ?? $productFeature->product_id);
                    }),
            ],
            'description' => ['nullable', 'string'],
            'type' => ['sometimes', 'required', 'string', 'in:boolean,numeric,text,list'],
            'value' => ['nullable', 'string'],
            'options' => ['nullable', 'array', 'required_if:type,list'],
            'options.*' => ['string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_highlighted' => ['nullable', 'boolean'],
            'is_public' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'product_id.exists' => 'The selected product does not exist.',
            'name.required' => 'The feature name is required.',
            'key.required' => 'The feature key is required.',
            'key.regex' => 'The feature key may only contain lowercase letters, numbers, dashes and underscores.',
            'key.unique' => 'This feature key already exists for this product.',
            'type.required' => 'The feature type is required.',
            'type.in' => 'The feature type must be boolean, numeric, text, or list.',
            'options.required_if' => 'Options are required when type is list.',
            'sort_order.integer' => 'The sort order must be a number.',
            'sort_order.min' => 'The sort order cannot be negative.',
        ];
    }
}
