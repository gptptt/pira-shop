@extends('layouts.admin')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Product Feature Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.product-features.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
            <a href="{{ route('admin.product-features.edit', $productFeature) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Feature
            </a>
            <form action="{{ route('admin.product-features.destroy', $productFeature) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this feature?')">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Feature Information</h2>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Name</p>
                        <p class="text-base text-gray-900">{{ $productFeature->name }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Key</p>
                        <p class="text-base text-gray-900">{{ $productFeature->key }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Type</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $productFeature->type === 'boolean' ? 'bg-blue-100 text-blue-800' : 
                           ($productFeature->type === 'numeric' ? 'bg-green-100 text-green-800' : 
                           ($productFeature->type === 'text' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800')) }}">
                            {{ ucfirst($productFeature->type) }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Product</p>
                        <p class="text-base text-gray-900">
                            <a href="{{ route('admin.products.show', $productFeature->product_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $productFeature->product->name ?? 'N/A' }}
                            </a>
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Default Value</p>
                        <p class="text-base text-gray-900">{{ $productFeature->default_value ?? 'None' }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Sort Order</p>
                        <p class="text-base text-gray-900">{{ $productFeature->sort_order }}</p>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Feature Status</h2>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="mt-1">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $productFeature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $productFeature->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Highlighted</p>
                        <p class="mt-1">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $productFeature->is_highlighted ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $productFeature->is_highlighted ? 'Highlighted' : 'Not Highlighted' }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Visibility</p>
                        <p class="mt-1">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $productFeature->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $productFeature->is_public ? 'Public' : 'Private' }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Created At</p>
                        <p class="text-base text-gray-900">{{ $productFeature->created_at->format('F j, Y, g:i a') }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Last Updated</p>
                        <p class="text-base text-gray-900">{{ $productFeature->updated_at->format('F j, Y, g:i a') }}</p>
                    </div>
                </div>
            </div>
            
            @if($productFeature->description)
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Description</h2>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-base text-gray-900">{{ $productFeature->description }}</p>
                </div>
            </div>
            @endif
            
            @if($productFeature->type === 'list' && !empty($productFeature->options))
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Options</h2>
                <div class="bg-gray-50 p-4 rounded">
                    <ul class="list-disc list-inside">
                        @foreach(is_array($productFeature->options) ? $productFeature->options : [$productFeature->options] as $option)
                            <li class="text-base text-gray-900">{{ $option }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 