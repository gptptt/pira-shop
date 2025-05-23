@props(['fluid' => false, 'py' => 'md', 'px' => 'md'])

@php
    $containerClass = $fluid ? 'w-full' : 'max-w-7xl mx-auto';
    
    // Padding configurations
    $paddingY = [
        'none' => 'py-0',
        'xs' => 'py-1',
        'sm' => 'py-2',
        'md' => 'py-4',
        'lg' => 'py-6',
        'xl' => 'py-8'
    ];
    
    $paddingX = [
        'none' => 'px-0',
        'xs' => 'px-1',
        'sm' => 'px-2',
        'md' => 'px-4 sm:px-6 lg:px-8',
        'lg' => 'px-6 sm:px-8 lg:px-10',
        'xl' => 'px-8 sm:px-10 lg:px-12'
    ];
    
    $pyClass = $paddingY[$py] ?? $paddingY['md'];
    $pxClass = $paddingX[$px] ?? $paddingX['md'];
@endphp

<div {{ $attributes->merge(['class' => $containerClass . ' ' . $pyClass . ' ' . $pxClass]) }}>
    {{ $slot }}
</div> 