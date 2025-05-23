@props(['title', 'value', 'icon', 'color' => 'primary', 'route' => null])

@php
    $colors = [
        'primary' => 'bg-indigo-100 text-indigo-600',
        'secondary' => 'bg-gray-100 text-gray-600',
        'success' => 'bg-green-100 text-green-600',
        'danger' => 'bg-red-100 text-red-600',
        'warning' => 'bg-yellow-100 text-yellow-600',
        'info' => 'bg-blue-100 text-blue-600'
    ];
    
    $textColors = [
        'primary' => 'text-indigo-600',
        'secondary' => 'text-gray-600',
        'success' => 'text-green-600',
        'danger' => 'text-red-600',
        'warning' => 'text-yellow-600',
        'info' => 'text-blue-600'
    ];
    
    $bgColor = $colors[$color] ?? $colors['primary'];
    $textColor = $textColors[$color] ?? $textColors['primary'];
@endphp

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 mb-4">
    <div class="p-4">
        <div class="flex justify-between items-center">
            <div>
                <h6 class="text-gray-500 text-sm font-medium mb-1">{{ $title }}</h6>
                <h4 class="text-2xl font-semibold mb-0">{{ $value }}</h4>
                @if(isset($trend))
                    <div class="mt-2">
                        {{ $trend }}
                    </div>
                @endif
            </div>
            <div class="rounded-full p-3 {{ $bgColor }}">
                <i class="fas fa-{{ $icon }} text-2xl"></i>
            </div>
        </div>
        @if($route)
            <div class="mt-3">
                <a href="{{ $route }}" class="inline-flex items-center text-sm font-medium no-underline {{ $textColor }} hover:underline">
                    View Details <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        @endif
    </div>
</div> 