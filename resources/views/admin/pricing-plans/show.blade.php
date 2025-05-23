@extends('layouts.admin')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pricing Plan Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.pricing-plans.edit', $pricingPlan) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <a href="{{ route('admin.pricing-plans.index', ['product_id' => $pricingPlan->product_id]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $pricingPlan->name }}</h2>
                    <p class="text-gray-500">{{ $pricingPlan->slug }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $pricingPlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $pricingPlan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($pricingPlan->is_featured)
                        <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Featured
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Plan Information</h3>
                
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-500">Product</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $pricingPlan->product->name ?? 'N/A' }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-500">Billing Cycle</p>
                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($pricingPlan->billing_cycle) }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-500">Price</p>
                    <p class="mt-1 text-sm text-gray-900">${{ number_format($pricingPlan->price, 2) }}</p>
                </div>
                
                @if($pricingPlan->billing_cycle === 'yearly' && $pricingPlan->monthly_price)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Monthly Equivalent</p>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($pricingPlan->monthly_price, 2) }}/month</p>
                    </div>
                @endif
                
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-500">Trial Days</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $pricingPlan->trial_days ?? 0 }}</p>
                </div>
                
                @if($pricingPlan->stripe_price_id)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Stripe Price ID</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $pricingPlan->stripe_price_id }}</p>
                    </div>
                @endif
                
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-500">Created At</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $pricingPlan->created_at->format('F j, Y, g:i a') }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-500">Last Updated</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $pricingPlan->updated_at->format('F j, Y, g:i a') }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Features</h3>
                
                @if(!empty($pricingPlan->features) && is_array($pricingPlan->features))
                    <ul class="space-y-2 mt-2">
                        @foreach($pricingPlan->features as $feature)
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @elseif(!empty($pricingPlan->features) && is_string($pricingPlan->features))
                    <p class="text-gray-700">{{ $pricingPlan->features }}</p>
                @else
                    <p class="text-gray-500 italic">No features specified</p>
                @endif
                
                @if(!empty($pricingPlan->metadata))
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Metadata</h3>
                        <div class="bg-gray-50 p-3 rounded">
                            <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ json_encode($pricingPlan->metadata, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
            <form action="{{ route('admin.pricing-plans.destroy', $pricingPlan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this pricing plan? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                    Delete Pricing Plan
                </button>
            </form>
            
            <div class="flex space-x-4">
                <form action="{{ route('admin.pricing-plans.toggle-status', $pricingPlan) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-blue-600 hover:text-blue-900 font-medium">
                        {{ $pricingPlan->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                
                <form action="{{ route('admin.pricing-plans.toggle-featured', $pricingPlan) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-purple-600 hover:text-purple-900 font-medium">
                        {{ $pricingPlan->is_featured ? 'Unfeature' : 'Make Featured' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 