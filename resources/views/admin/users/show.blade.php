@extends('layouts.admin')

@section('content')
<div class="w-full">
    @component('shared.components.dashboard.main', [
        'title' => 'User Details',
        'subtitle' => 'View user information and roles'
    ])
        @slot('actions')
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i> Edit User
            </a>
        @endslot

        @slot('content')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm h-full">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h6 class="text-sm font-medium text-gray-700">Profile</h6>
                        </div>
                        <div class="p-4 flex flex-col items-center">
                            <div class="w-24 h-24 bg-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-semibold mb-4">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->full_name }}" class="w-24 h-24 rounded-full object-cover">
                                @else
                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->full_name }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
                            
                            <div class="w-full border-t border-gray-200 pt-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-500">Member Since:</span>
                                    <span class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Last Updated:</span>
                                    <span class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm h-full">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h6 class="text-sm font-medium text-gray-700">User Information</h6>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->first_name }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->last_name }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</h4>
                                    <p class="mt-1">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Unverified
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Roles</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500">No roles assigned</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h6 class="text-sm font-medium text-gray-700">Activity Log</h6>
                </div>
                <div class="p-4">
                    <!-- This is a placeholder for future activity log implementation -->
                    <p class="text-sm text-gray-500 text-center py-4">Activity log will be available in a future update.</p>
                </div>
            </div>
        @endslot
    @endcomponent
</div>
@endsection 