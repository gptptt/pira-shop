@extends('layouts.admin')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Enterprise Pricing Tier</h1>
        <a href="{{ route('admin.pricing-plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.pricing-plans.store-enterprise-tier') }}" method="POST">
            @csrf
            
            <!-- Product Selection -->
            <div class="mb-6">
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Product <span class="text-red-600">*</span></label>
                <select name="product_id" id="product_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('product_id') border-red-500 @enderror" required>
                    <option value="">Select Product</option>
                    @foreach($products as $id => $name)
                        <option value="{{ $id }}" {{ old('product_id', $productId ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Enterprise Tier Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Plan Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', 'Enterprise') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Enterprise Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', 'enterprise') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('slug') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Display Price Text -->
                <div>
                    <label for="display_price" class="block text-sm font-medium text-gray-700 mb-1">Display Price Text <span class="text-red-600">*</span></label>
                    <input type="text" name="display_price" id="display_price" value="{{ old('display_price', 'Contact Sales') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('display_price') border-red-500 @enderror" required>
                    <p class="text-gray-500 text-xs mt-1">Text to display instead of a fixed price (e.g. "Contact Sales", "Custom Pricing")</p>
                    @error('display_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Email -->
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Contact Email <span class="text-red-600">*</span></label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', 'sales@example.com') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('contact_email') border-red-500 @enderror" required>
                    @error('contact_email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact URL -->
                <div>
                    <label for="contact_url" class="block text-sm font-medium text-gray-700 mb-1">Contact URL</label>
                    <input type="url" name="contact_url" id="contact_url" value="{{ old('contact_url', 'https://example.com/contact-sales') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('contact_url') border-red-500 @enderror">
                    @error('contact_url')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CTA Button Text -->
                <div>
                    <label for="cta_text" class="block text-sm font-medium text-gray-700 mb-1">CTA Button Text</label>
                    <input type="text" name="cta_text" id="cta_text" value="{{ old('cta_text', 'Contact Sales') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('cta_text') border-red-500 @enderror">
                    @error('cta_text')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Features -->
            <div class="mt-6">
                <label for="features" class="block text-sm font-medium text-gray-700 mb-1">Features</label>
                <textarea name="features" id="features" rows="5" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('features') border-red-500 @enderror">{{ old('features', "Unlimited transcripts\nUnlimited duration\nHighest accuracy\nCustom vocabulary\nSpeaker identification\nExport to multiple formats\nPriority support\nDedicated account manager\nCustom integration\nSLA guarantees\nBulk volume discounts\nCustom training") }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Enter one feature per line</p>
                @error('features')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Minimum Seats -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="min_seats" class="block text-sm font-medium text-gray-700 mb-1">Minimum Seats</label>
                    <input type="number" name="min_seats" id="min_seats" min="1" value="{{ old('min_seats', '10') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('min_seats') border-red-500 @enderror">
                    @error('min_seats')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Default Price Per Seat -->
                <div>
                    <label for="price_per_seat" class="block text-sm font-medium text-gray-700 mb-1">Default Price Per Seat</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="price_per_seat" id="price_per_seat" step="0.01" min="0" value="{{ old('price_per_seat', '99.99') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('price_per_seat') border-red-500 @enderror">
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Internal pricing for calculation purposes only</p>
                    @error('price_per_seat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status & Featured Toggles -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-700">Featured</label>
                </div>
            </div>

            <!-- Additional Metadata -->
            <div class="mt-6">
                <label for="metadata" class="block text-sm font-medium text-gray-700 mb-1">Metadata (JSON)</label>
                <textarea name="metadata" id="metadata" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('metadata') border-red-500 @enderror">{{ old('metadata', '{"enterprise_plan": true, "custom_pricing": true, "requires_approval": true}') }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Optional JSON metadata for the plan</p>
                @error('metadata')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Enterprise Tier
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-generate slug from name
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                const slugValue = this.value
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
                
                slugInput.value = slugValue;
            });
        }
    });
</script>
@endsection 