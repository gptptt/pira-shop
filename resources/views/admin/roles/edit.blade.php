@extends('layouts.admin')

@section('content')
<div class="w-full">
    @component('shared.components.dashboard.main', [
        'title' => 'Edit Role',
        'subtitle' => 'Update role details and permissions'
    ])
        @slot('actions')
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Roles
            </a>
        @endslot

        @slot('content')
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h6 class="text-sm font-medium text-gray-700">Edit Role: {{ ucfirst($role->name) }}</h6>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $errors->has('name') ? 'border-red-300' : '' }}" 
                                id="name" name="name" value="{{ old('name', $role->name) }}" required
                                {{ in_array($role->name, ['admin', 'customer']) ? 'readonly' : '' }}>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                @if(in_array($role->name, ['admin', 'customer']))
                                    This is a system role and cannot be renamed.
                                @else
                                    Enter a unique name for this role.
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                            <div class="bg-white border border-gray-200 rounded-md shadow-sm">
                                <div class="p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        @forelse($permissions as $permission)
                                            <div class="mb-2">
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" type="checkbox" 
                                                            name="permissions[]" value="{{ $permission->name }}" 
                                                            id="permission_{{ $permission->id }}"
                                                            {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label class="font-medium text-gray-700" for="permission_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-span-3">
                                                <p class="text-sm text-gray-500">No permissions available.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endslot
    @endcomponent
</div>
@endsection 