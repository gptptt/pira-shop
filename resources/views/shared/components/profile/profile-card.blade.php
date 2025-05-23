@props(['title', 'editRoute' => null])

<x-card :title="$title" class="mb-4">
    <x-slot:headerActions>
        @if($editRoute)
            <a href="{{ $editRoute }}" class="inline-flex items-center px-3 py-2 border border-indigo-500 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Edit
            </a>
        @endif
    </x-slot:headerActions>
    
    {{ $slot }}
</x-card> 