@extends('layouts.admin')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Product Feature</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.product-features.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
            <a href="{{ route('admin.product-features.show', $productFeature) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                View Feature
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.product-features.update', $productFeature) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Selection -->
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Product <span class="text-red-600">*</span></label>
                    <select name="product_id" id="product_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('product_id') border-red-500 @enderror" required>
                        <option value="">Select Product</option>
                        @foreach($products as $id => $name)
                            <option value="{{ $id }}" {{ old('product_id', $productFeature->product_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Feature Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Feature Type <span class="text-red-600">*</span></label>
                    <select name="type" id="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('type') border-red-500 @enderror" required>
                        <option value="">Select Type</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $productFeature->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Feature Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $productFeature->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Key -->
                <div>
                    <label for="key" class="block text-sm font-medium text-gray-700 mb-1">Feature Key <span class="text-red-600">*</span></label>
                    <input type="text" name="key" id="key" value="{{ old('key', $productFeature->key) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('key') border-red-500 @enderror" required>
                    <p class="text-gray-500 text-xs mt-1">Unique identifier for the feature</p>
                    @error('key')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Default Value -->
                <div>
                    <label for="default_value" class="block text-sm font-medium text-gray-700 mb-1">Default Value</label>
                    <input type="text" name="default_value" id="default_value" value="{{ old('default_value', $productFeature->default_value) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('default_value') border-red-500 @enderror">
                    @error('default_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $productFeature->sort_order) }}" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror">{{ old('description', $productFeature->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options (for list type) -->
            <div class="mt-6" id="options-container">
                <label for="options" class="block text-sm font-medium text-gray-700 mb-1">Options</label>
                <textarea name="options" id="options" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('options') border-red-500 @enderror">{{ old('options', is_array($productFeature->options) ? implode("\n", $productFeature->options) : $productFeature->options) }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Enter one option per line. Used for 'list' type features.</p>
                @error('options')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status & Visibility Toggles -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $productFeature->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_highlighted" id="is_highlighted" value="1" {{ old('is_highlighted', $productFeature->is_highlighted) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_highlighted" class="ml-2 block text-sm text-gray-700">Highlighted</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', $productFeature->is_public) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_public" class="ml-2 block text-sm text-gray-700">Public</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Feature
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const optionsContainer = document.getElementById('options-container');
        
        // Helper to toggle visibility of options field based on type
        function toggleOptionsVisibility() {
            if (typeSelect.value === 'list') {
                optionsContainer.classList.remove('hidden');
            } else {
                optionsContainer.classList.add('hidden');
            }
        }
        
        // Initial visibility check
        toggleOptionsVisibility();
        
        // Add event listener for type change
        if (typeSelect) {
            typeSelect.addEventListener('change', toggleOptionsVisibility);
        }
    });
</script>
@endsection 