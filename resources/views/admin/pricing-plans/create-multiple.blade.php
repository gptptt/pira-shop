@extends('layouts.admin')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Multiple Pricing Tiers</h1>
        <a href="{{ route('admin.pricing-plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.pricing-plans.store-multiple-tiers') }}" method="POST">
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

            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Tier</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <!-- Basic Tier Name -->
                    <div>
                        <label for="tiers[0][name]" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-600">*</span></label>
                        <input type="text" name="tiers[0][name]" id="tiers[0][name]" value="{{ old('tiers.0.name', 'Basic') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.0.name') border-red-500 @enderror" required>
                        @error('tiers.0.name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Monthly Price -->
                    <div>
                        <label for="tiers[0][monthly_price]" class="block text-sm font-medium text-gray-700 mb-1">Monthly Price <span class="text-red-600">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="tiers[0][monthly_price]" id="tiers[0][monthly_price]" step="0.01" min="0" value="{{ old('tiers.0.monthly_price', '9.99') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.0.monthly_price') border-red-500 @enderror" required>
                        </div>
                        @error('tiers.0.monthly_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Yearly Price -->
                    <div>
                        <label for="tiers[0][yearly_price]" class="block text-sm font-medium text-gray-700 mb-1">Yearly Price <span class="text-red-600">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="tiers[0][yearly_price]" id="tiers[0][yearly_price]" step="0.01" min="0" value="{{ old('tiers.0.yearly_price', '99.99') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.0.yearly_price') border-red-500 @enderror" required>
                        </div>
                        @error('tiers.0.yearly_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Features -->
                    <div class="md:col-span-2">
                        <label for="tiers[0][features]" class="block text-sm font-medium text-gray-700 mb-1">Features</label>
                        <textarea name="tiers[0][features]" id="tiers[0][features]" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.0.features') border-red-500 @enderror">{{ old('tiers.0.features', "10 Transcripts per month\n30 minutes maximum per transcript\nStandard accuracy") }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Enter one feature per line</p>
                        @error('tiers.0.features')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Trial Days -->
                    <div>
                        <label for="tiers[0][trial_days]" class="block text-sm font-medium text-gray-700 mb-1">Trial Days</label>
                        <input type="number" name="tiers[0][trial_days]" id="tiers[0][trial_days]" min="0" value="{{ old('tiers.0.trial_days', '7') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.0.trial_days') border-red-500 @enderror">
                        @error('tiers.0.trial_days')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Featured -->
                    <div class="flex items-center">
                        <input type="checkbox" name="tiers[0][is_featured]" id="tiers[0][is_featured]" value="1" {{ old('tiers.0.is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="tiers[0][is_featured]" class="ml-2 block text-sm text-gray-700">Featured</label>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Standard Tier</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <!-- Standard Tier Name -->
                    <div>
                        <label for="tiers[1][name]" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-600">*</span></label>
                        <input type="text" name="tiers[1][name]" id="tiers[1][name]" value="{{ old('tiers.1.name', 'Standard') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.1.name') border-red-500 @enderror" required>
                        @error('tiers.1.name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Standard Monthly Price -->
                    <div>
                        <label for="tiers[1][monthly_price]" class="block text-sm font-medium text-gray-700 mb-1">Monthly Price <span class="text-red-600">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="tiers[1][monthly_price]" id="tiers[1][monthly_price]" step="0.01" min="0" value="{{ old('tiers.1.monthly_price', '19.99') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.1.monthly_price') border-red-500 @enderror" required>
                        </div>
                        @error('tiers.1.monthly_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Standard Yearly Price -->
                    <div>
                        <label for="tiers[1][yearly_price]" class="block text-sm font-medium text-gray-700 mb-1">Yearly Price <span class="text-red-600">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="tiers[1][yearly_price]" id="tiers[1][yearly_price]" step="0.01" min="0" value="{{ old('tiers.1.yearly_price', '199.99') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.1.yearly_price') border-red-500 @enderror" required>
                        </div>
                        @error('tiers.1.yearly_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Standard Features -->
                    <div class="md:col-span-2">
                        <label for="tiers[1][features]" class="block text-sm font-medium text-gray-700 mb-1">Features</label>
                        <textarea name="tiers[1][features]" id="tiers[1][features]" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.1.features') border-red-500 @enderror">{{ old('tiers.1.features', "30 Transcripts per month\n60 minutes maximum per transcript\nHigh accuracy\nCustom vocabulary\nSpeaker identification") }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Enter one feature per line</p>
                        @error('tiers.1.features')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Standard Trial Days -->
                    <div>
                        <label for="tiers[1][trial_days]" class="block text-sm font-medium text-gray-700 mb-1">Trial Days</label>
                        <input type="number" name="tiers[1][trial_days]" id="tiers[1][trial_days]" min="0" value="{{ old('tiers.1.trial_days', '7') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.1.trial_days') border-red-500 @enderror">
                        @error('tiers.1.trial_days')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Standard Featured -->
                    <div class="flex items-center">
                        <input type="checkbox" name="tiers[1][is_featured]" id="tiers[1][is_featured]" value="1" {{ old('tiers.1.is_featured', '1') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="tiers[1][is_featured]" class="ml-2 block text-sm text-gray-700">Featured</label>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Premium Tier</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <!-- Premium Tier Name -->
                    <div>
                        <label for="tiers[2][name]" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-600">*</span></label>
                        <input type="text" name="tiers[2][name]" id="tiers[2][name]" value="{{ old('tiers.2.name', 'Premium') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.2.name') border-red-500 @enderror" required>
                        @error('tiers.2.name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Premium Monthly Price -->
                    <div>
                        <label for="tiers[2][monthly_price]" class="block text-sm font-medium text-gray-700 mb-1">Monthly Price <span class="text-red-600">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="tiers[2][monthly_price]" id="tiers[2][monthly_price]" step="0.01" min="0" value="{{ old('tiers.2.monthly_price', '39.99') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.2.monthly_price') border-red-500 @enderror" required>
                        </div>
                        @error('tiers.2.monthly_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Premium Yearly Price -->
                    <div>
                        <label for="tiers[2][yearly_price]" class="block text-sm font-medium text-gray-700 mb-1">Yearly Price <span class="text-red-600">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="tiers[2][yearly_price]" id="tiers[2][yearly_price]" step="0.01" min="0" value="{{ old('tiers.2.yearly_price', '399.99') }}" class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.2.yearly_price') border-red-500 @enderror" required>
                        </div>
                        @error('tiers.2.yearly_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Premium Features -->
                    <div class="md:col-span-2">
                        <label for="tiers[2][features]" class="block text-sm font-medium text-gray-700 mb-1">Features</label>
                        <textarea name="tiers[2][features]" id="tiers[2][features]" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.2.features') border-red-500 @enderror">{{ old('tiers.2.features', "Unlimited transcripts\nUnlimited duration\nHighest accuracy\nCustom vocabulary\nSpeaker identification\nExport to multiple formats\nPriority support\nAPI access") }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Enter one feature per line</p>
                        @error('tiers.2.features')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Premium Trial Days -->
                    <div>
                        <label for="tiers[2][trial_days]" class="block text-sm font-medium text-gray-700 mb-1">Trial Days</label>
                        <input type="number" name="tiers[2][trial_days]" id="tiers[2][trial_days]" min="0" value="{{ old('tiers.2.trial_days', '7') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tiers.2.trial_days') border-red-500 @enderror">
                        @error('tiers.2.trial_days')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Premium Featured -->
                    <div class="flex items-center">
                        <input type="checkbox" name="tiers[2][is_featured]" id="tiers[2][is_featured]" value="1" {{ old('tiers.2.is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="tiers[2][is_featured]" class="ml-2 block text-sm text-gray-700">Featured</label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Pricing Tiers
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 