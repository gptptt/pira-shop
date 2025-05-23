@extends('layouts.admin')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Pricing Plan</h1>
        <a href="{{ route('admin.pricing-plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.pricing-plans.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Selection -->
                <div>
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

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Plan Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('slug') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Billing Cycle -->
                <div>
                    <label for="billing_cycle" class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle <span class="text-red-600">*</span></label>
                    <select name="billing_cycle" id="billing_cycle" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('billing_cycle') border-red-500 @enderror" required>
                        <option value="monthly" {{ old('billing_cycle', $billingCycle ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('billing_cycle', $billingCycle ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        <option value="custom" {{ old('billing_cycle', $billingCycle ?? '') == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                    @error('billing_cycle')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price <span class="text-red-600">*</span></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('price') border-red-500 @enderror" required>
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monthly Price (Optional) -->
                <div>
                    <label for="monthly_price" class="block text-sm font-medium text-gray-700 mb-1">Monthly Equivalent Price</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="monthly_price" id="monthly_price" step="0.01" min="0" value="{{ old('monthly_price') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('monthly_price') border-red-500 @enderror">
                    </div>
                    <p class="text-gray-500 text-xs mt-1">For yearly plans, this shows as the monthly equivalent price</p>
                    @error('monthly_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stripe Price ID (Optional) -->
                <div>
                    <label for="stripe_price_id" class="block text-sm font-medium text-gray-700 mb-1">Stripe Price ID</label>
                    <input type="text" name="stripe_price_id" id="stripe_price_id" value="{{ old('stripe_price_id') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('stripe_price_id') border-red-500 @enderror">
                    @error('stripe_price_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trial Days -->
                <div>
                    <label for="trial_days" class="block text-sm font-medium text-gray-700 mb-1">Trial Days</label>
                    <input type="number" name="trial_days" id="trial_days" min="0" value="{{ old('trial_days', 0) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('trial_days') border-red-500 @enderror">
                    @error('trial_days')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Features -->
            <div class="mt-6">
                <label for="features" class="block text-sm font-medium text-gray-700 mb-1">Features</label>
                <textarea name="features" id="features" rows="5" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('features') border-red-500 @enderror">{{ old('features') }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Enter one feature per line</p>
                @error('features')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status & Featured Toggles -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-700">Featured</label>
                </div>
            </div>

            <!-- Metadata -->
            <div class="mt-6">
                <label for="metadata" class="block text-sm font-medium text-gray-700 mb-1">Metadata (JSON)</label>
                <textarea name="metadata" id="metadata" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('metadata') border-red-500 @enderror">{{ old('metadata') }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Optional JSON metadata for the plan</p>
                @error('metadata')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Pricing Plan
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
                if (!slugInput.value) {
                    const slugValue = this.value
                        .toLowerCase()
                        .replace(/\s+/g, '-')
                        .replace(/[^\w\-]+/g, '')
                        .replace(/\-\-+/g, '-')
                        .replace(/^-+/, '')
                        .replace(/-+$/, '');
                    
                    slugInput.value = slugValue;
                }
            });
        }
    });
</script>
@endsection 