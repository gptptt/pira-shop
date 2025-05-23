@props(['cols' => 1, 'gap' => 'md', 'rowGap' => null, 'colGap' => null])

@php
    // Column configuration
    $colClasses = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 sm:grid-cols-2',
        3 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        5 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5',
        6 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6',
        7 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7',
        8 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8',
        9 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-9',
        10 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-10',
        11 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-11',
        12 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-12'
    ];
    
    // Gap configuration
    $gapSizes = [
        'none' => 'gap-0',
        'xs' => 'gap-1',
        'sm' => 'gap-2',
        'md' => 'gap-4',
        'lg' => 'gap-6',
        'xl' => 'gap-8'
    ];
    
    // Default gap
    $gapClass = $gapSizes[$gap] ?? $gapSizes['md'];
    
    // Row and column gaps
    if ($rowGap && $colGap) {
        $rowGapClass = isset($gapSizes[$rowGap]) ? str_replace('gap-', 'gap-y-', $gapSizes[$rowGap]) : 'gap-y-4';
        $colGapClass = isset($gapSizes[$colGap]) ? str_replace('gap-', 'gap-x-', $gapSizes[$colGap]) : 'gap-x-4';
        $gapClass = $rowGapClass . ' ' . $colGapClass;
    }
    
    // Column class
    $gridClass = isset($colClasses[$cols]) ? $colClasses[$cols] : $colClasses[1];
@endphp

<div {{ $attributes->merge(['class' => 'grid ' . $gridClass . ' ' . $gapClass]) }}>
    {{ $slot }}
</div> 