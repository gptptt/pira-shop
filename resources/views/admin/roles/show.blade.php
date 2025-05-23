@extends('layouts.admin')

@section('content')
<div class="w-full">
    @component('shared.components.dashboard.main', [
        'title' => 'Role Details',
        'subtitle' => 'View role permissions and assigned users'
    ])
        @slot('actions')
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                <i class="fas fa-arrow-left mr-2"></i> Back to Roles
            </a>
            <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i> Edit Role
            </a>
        @endslot

        @slot('content')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="bg-white rounded-lg shadow-sm h-full">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h6 class="text-sm font-medium text-gray-700">Role Information</h6>
                        </div>
                        <div class="p-4">
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-700">Name:</label>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($role->name) }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-700">Guard:</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $role->guard_name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-700">Created:</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $role->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-700">Last Updated:</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $role->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div class="bg-white rounded-lg shadow-sm h-full">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h6 class="text-sm font-medium text-gray-700">Permissions ({{ $role->permissions->count() }})</h6>
                        </div>
                        <div class="p-4">
                            @if($role->permissions->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($role->permissions as $permission)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $permission->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">This role has no permissions assigned.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <h6 class="text-sm font-medium text-gray-700">Users with this Role ({{ $role->users->count() }})</h6>
                </div>
                @if($role->users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($role->users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->full_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-indigo-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-4 py-5 text-center text-sm text-gray-500">
                        No users have been assigned this role yet.
                    </div>
                @endif
            </div>
        @endslot
    @endcomponent
</div>
@endsection 