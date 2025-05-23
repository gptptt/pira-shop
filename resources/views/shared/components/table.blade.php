@props([
    'striped' => false,
    'hover' => false,
    'bordered' => false,
    'responsive' => true,
    'compact' => false
])

@php
    $tableClasses = 'min-w-full divide-y divide-gray-200';
    
    if ($bordered) {
        $tableClasses .= ' border border-gray-200';
    }
@endphp

@if($responsive)
<div class="overflow-x-auto shadow-sm rounded-lg">
@endif
    <table {{ $attributes->merge(['class' => $tableClasses]) }}>
        @isset($head)
        <thead class="bg-gray-50">
            {{ $head }}
        </thead>
        @endisset
        
        <tbody class="bg-white divide-y divide-gray-200 @if($striped) divide-y-0 @endif">
            {{ $slot }}
        </tbody>
        
        @isset($footer)
        <tfoot class="bg-gray-50">
            {{ $footer }}
        </tfoot>
        @endisset
    </table>
@if($responsive)
</div>
@endif 