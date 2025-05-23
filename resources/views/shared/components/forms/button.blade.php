@props([
    'type' => 'button',
    'variant' => 'primary', 
    'size' => 'md',
    'disabled' => false,
    'icon' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center border font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors';
    
    $variants = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 border-transparent text-white focus:ring-indigo-500',
        'secondary' => 'bg-white hover:bg-gray-50 border-gray-300 text-gray-700 focus:ring-indigo-500',
        'success' => 'bg-green-600 hover:bg-green-700 border-transparent text-white focus:ring-green-500',
        'danger' => 'bg-red-600 hover:bg-red-700 border-transparent text-white focus:ring-red-500',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 border-transparent text-white focus:ring-yellow-500',
        'info' => 'bg-blue-600 hover:bg-blue-700 border-transparent text-white focus:ring-blue-500',
        'light' => 'bg-gray-100 hover:bg-gray-200 border-gray-300 text-gray-700 focus:ring-gray-400',
        'dark' => 'bg-gray-800 hover:bg-gray-900 border-transparent text-white focus:ring-gray-700',
        'outline-primary' => 'bg-transparent hover:bg-indigo-50 border-indigo-500 text-indigo-600 hover:text-indigo-700 focus:ring-indigo-500',
        'outline-secondary' => 'bg-transparent hover:bg-gray-50 border-gray-300 text-gray-700 hover:text-gray-800 focus:ring-gray-400',
        'outline-danger' => 'bg-transparent hover:bg-red-50 border-red-500 text-red-600 hover:text-red-700 focus:ring-red-500',
        'outline-info' => 'bg-transparent hover:bg-blue-50 border-blue-500 text-blue-600 hover:text-blue-700 focus:ring-blue-500',
        'link' => 'bg-transparent hover:bg-gray-50 border-transparent text-indigo-600 hover:text-indigo-700 focus:ring-indigo-500 shadow-none',
    ];
    
    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm leading-4',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        'xl' => 'px-6 py-3 text-base',
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
    if ($disabled) {
        $classes .= ' opacity-50 cursor-not-allowed';
    }
@endphp

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }} 
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($icon)
        <i class="{{ $icon }} {{ !$slot->isEmpty() ? 'mr-2' : '' }}"></i>
    @endif
    
    {{ $slot }}
</button> 