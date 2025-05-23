@props(['span' => '12', 'sm' => null, 'md' => null, 'lg' => null, 'xl' => null])

@php
    // Base column span
    $colSpans = [
        '1' => 'col-span-1',
        '2' => 'col-span-2',
        '3' => 'col-span-3',
        '4' => 'col-span-4',
        '5' => 'col-span-5',
        '6' => 'col-span-6',
        '7' => 'col-span-7',
        '8' => 'col-span-8',
        '9' => 'col-span-9',
        '10' => 'col-span-10',
        '11' => 'col-span-11',
        '12' => 'col-span-12',
        'auto' => 'col-auto',
        'full' => 'col-span-full'
    ];
    
    // Responsive spans
    $responsiveSpans = [];
    
    if ($sm) {
        $responsiveSpans[] = isset($colSpans[$sm]) ? 'sm:' . $colSpans[$sm] : 'sm:col-span-' . $sm;
    }
    
    if ($md) {
        $responsiveSpans[] = isset($colSpans[$md]) ? 'md:' . $colSpans[$md] : 'md:col-span-' . $md;
    }
    
    if ($lg) {
        $responsiveSpans[] = isset($colSpans[$lg]) ? 'lg:' . $colSpans[$lg] : 'lg:col-span-' . $lg;
    }
    
    if ($xl) {
        $responsiveSpans[] = isset($colSpans[$xl]) ? 'xl:' . $colSpans[$xl] : 'xl:col-span-' . $xl;
    }
    
    // Combine base and responsive spans
    $baseSpan = isset($colSpans[$span]) ? $colSpans[$span] : 'col-span-' . $span;
    
    $classes = $baseSpan . ' ' . implode(' ', $responsiveSpans);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div> 