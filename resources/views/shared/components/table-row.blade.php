@props(['striped' => false, 'hover' => true, 'highlighted' => false])

@php
    $classes = '';
    
    if ($striped) {
        $classes .= ' even:bg-gray-50';
    }
    
    if ($hover) {
        $classes .= ' hover:bg-gray-50';
    }
    
    if ($highlighted) {
        $classes .= ' bg-indigo-50';
    }
@endphp

<tr {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</tr> 