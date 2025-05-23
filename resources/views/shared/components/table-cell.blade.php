@props(['align' => 'left', 'compact' => false, 'type' => 'data'])

@php
    $baseClasses = '';
    
    if ($compact) {
        $baseClasses .= 'px-3 py-2 ';
    } else {
        $baseClasses .= 'px-6 py-4 ';
    }
    
    if ($align === 'left') {
        $baseClasses .= 'text-left ';
    } elseif ($align === 'center') {
        $baseClasses .= 'text-center ';
    } elseif ($align === 'right') {
        $baseClasses .= 'text-right ';
    }
    
    if ($type === 'data') {
        $baseClasses .= 'whitespace-nowrap text-sm text-gray-500';
    } else {
        $baseClasses .= 'font-medium text-gray-900';
    }
@endphp

@if($type === 'header')
    <th {{ $attributes->merge(['class' => $baseClasses, 'scope' => 'row']) }}>
        {{ $slot }}
    </th>
@else
    <td {{ $attributes->merge(['class' => $baseClasses]) }}>
        {{ $slot }}
    </td>
@endif 