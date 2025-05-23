<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('product'));
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
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('products')->ignore($this->route('product')),
            ],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
            'is_active' => ['nullable', 'boolean'],
            'visibility' => ['nullable', 'string', 'in:public,private,restricted'],
            'availability' => ['nullable', 'string', 'in:coming_soon,available,discontinued'],
            'is_featured' => ['nullable', 'boolean'],
            'show_on_homepage' => ['nullable', 'boolean'],
            'is_highlighted' => ['nullable', 'boolean'],
            'publish_at' => ['nullable', 'date'],
            'unpublish_at' => ['nullable', 'date', 'after:publish_at'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
            'metadata.recommended' => ['nullable', 'boolean'],
            'metadata.popular' => ['nullable', 'boolean'],
            'metadata.new' => ['nullable', 'boolean'],
            'metadata.category' => ['nullable', 'string', 'max:50'],
            'metadata.icon' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:2048'], // For image uploads
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
            'name.required' => 'The product name is required.',
            'name.max' => 'The product name cannot exceed 255 characters.',
            'slug.unique' => 'This slug is already in use. Please choose a different one.',
            'features.array' => 'Features must be provided as an array.',
            'features.*.string' => 'Each feature must be a string.',
            'visibility.in' => 'Visibility must be public, private, or restricted.',
            'availability.in' => 'Availability must be coming_soon, available, or discontinued.',
            'publish_at.date' => 'Publish date must be a valid date.',
            'unpublish_at.date' => 'Unpublish date must be a valid date.',
            'unpublish_at.after' => 'Unpublish date must be after publish date.',
            'sort_order.integer' => 'Sort order must be a whole number.',
            'sort_order.min' => 'Sort order cannot be negative.',
            'metadata.array' => 'Metadata must be provided as an array.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image size cannot exceed 2MB.',
        ];
    }
}
