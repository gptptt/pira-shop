@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">{{ $product->name }}</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Products
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Basic Information -->
            <div class="md:col-span-2">
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h2 class="text-lg font-medium mb-4 border-b pb-2">Product Information</h2>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Product Name</h3>
                        <p class="text-gray-900">{{ $product->name }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Slug</h3>
                        <p class="text-gray-900">{{ $product->slug }}</p>
                    </div>
                    
                    @if($product->short_description)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Short Description</h3>
                        <p class="text-gray-900">{{ $product->short_description }}</p>
                    </div>
                    @endif
                    
                    @if($product->description)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Full Description</h3>
                        <div class="text-gray-900 prose max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                    @endif
                    
                    @if($product->features && is_array($product->features))
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Features</h3>
                        <ul class="list-disc pl-5 mt-2 text-gray-900">
                            @foreach($product->features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                
                <!-- Product Features -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium">Product Features</h2>
                        <a href="{{ route('admin.product-features.create', ['product_id' => $product->id]) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus-circle"></i> Add Feature
                        </a>
                    </div>
                    
                    @if($product->productFeatures && $product->productFeatures->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($product->productFeatures as $feature)
                                        <tr>
                                            <td class="px-4 py-2">
                                                <div class="font-medium text-gray-900">{{ $feature->name }}</div>
                                                <div class="text-gray-500 text-sm">{{ $feature->key }}</div>
                                            </td>
                                            <td class="px-4 py-2 text-gray-500">{{ ucfirst($feature->type) }}</td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $feature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $feature->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-right space-x-1">
                                                <a href="{{ route('admin.product-features.edit', $feature) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.product-features.show', $feature) }}" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No features added to this product yet.</p>
                    @endif
                </div>
                
                <!-- Pricing Plans -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium">Pricing Plans</h2>
                        <a href="{{ route('admin.pricing-plans.create', ['product_id' => $product->id]) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus-circle"></i> Add Plan
                        </a>
                    </div>
                    
                    @if($product->pricingPlans && $product->pricingPlans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Billing</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($product->pricingPlans as $plan)
                                        <tr>
                                            <td class="px-4 py-2">
                                                <div class="font-medium text-gray-900">{{ $plan->name }}</div>
                                                <div class="text-gray-500 text-sm">{{ $plan->slug }}</div>
                                            </td>
                                            <td class="px-4 py-2">
                                                @if($plan->billing_cycle == 'monthly')
                                                    ${{ number_format($plan->monthly_price, 2) }}/mo
                                                @elseif($plan->billing_cycle == 'yearly')
                                                    ${{ number_format($plan->yearly_price, 2) }}/yr
                                                @else
                                                    ${{ number_format($plan->custom_price, 2) }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 capitalize">{{ $plan->billing_cycle }}</td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-right space-x-1">
                                                <a href="{{ route('admin.pricing-plans.edit', $plan) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.pricing-plans.show', $plan) }}" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No pricing plans added to this product yet.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="md:col-span-1">
                <!-- Product Image -->
                @if($product->image_path)
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h2 class="text-lg font-medium mb-4 border-b pb-2">Product Image</h2>
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full rounded">
                    </div>
                @endif
                
                <!-- Status Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h2 class="text-lg font-medium mb-4 border-b pb-2">Status Information</h2>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Visibility</h3>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($product->visibility == 'public') bg-green-100 text-green-800
                            @elseif($product->visibility == 'private') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($product->visibility) }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Availability</h3>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($product->availability == 'available') bg-green-100 text-green-800
                            @elseif($product->availability == 'coming_soon') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $product->availability)) }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Display Options</h3>
                        <div class="space-y-2 mt-2">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ $product->is_featured ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                <span>{{ $product->is_featured ? 'Featured' : 'Not Featured' }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ $product->show_on_homepage ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                <span>{{ $product->show_on_homepage ? 'Shows on Homepage' : 'Hidden from Homepage' }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ $product->is_highlighted ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                <span>{{ $product->is_highlighted ? 'Highlighted' : 'Not Highlighted' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Publication</h3>
                        <div class="space-y-2 mt-2">
                            <div class="flex items-center">
                                <span class="text-gray-700 font-medium mr-2">Publish Date:</span>
                                <span>{{ $product->publish_at ? $product->publish_at->format('M d, Y') : 'Immediately' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-700 font-medium mr-2">Unpublish Date:</span>
                                <span>{{ $product->unpublish_at ? $product->unpublish_at->format('M d, Y') : 'Never' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Meta Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h2 class="text-lg font-medium mb-4 border-b pb-2">Meta Information</h2>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Sort Order</h3>
                        <p class="text-gray-900">{{ $product->sort_order ?? 0 }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Created</h3>
                        <p class="text-gray-900">{{ $product->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                        <p class="text-gray-900">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-medium mb-4 border-b pb-2">Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                            <i class="fas fa-edit mr-1"></i> Edit Product
                        </a>
                        
                        <a href="{{ route('admin.pricing-plans.create', ['product_id' => $product->id]) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
                            <i class="fas fa-plus-circle mr-1"></i> Add Pricing Plan
                        </a>
                        
                        <a href="{{ route('admin.product-features.create', ['product_id' => $product->id]) }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center">
                            <i class="fas fa-plus-circle mr-1"></i> Add Feature
                        </a>
                        
                        @if($product->is_active)
                            <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center">
                                    <i class="fas fa-toggle-off mr-1"></i> Deactivate
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center">
                                    <i class="fas fa-toggle-on mr-1"></i> Activate
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center">
                                <i class="fas fa-trash mr-1"></i> Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 