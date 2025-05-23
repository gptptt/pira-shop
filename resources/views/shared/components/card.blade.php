@props([
    'title' => null,
    'subtitle' => null,
    'headerActions' => null,
    'footer' => null,
    'noPadding' => false,
    'variant' => 'default'
])

@php
    $bodyClasses = $noPadding ? '' : 'p-4';
    
    $variants = [
        'default' => 'bg-white',
        'primary' => 'bg-indigo-50',
        'success' => 'bg-green-50',
        'danger' => 'bg-red-50',
        'warning' => 'bg-yellow-50',
        'info' => 'bg-blue-50',
    ];
    
    $headerVariants = [
        'default' => 'bg-gray-50 text-gray-700',
        'primary' => 'bg-indigo-100 text-indigo-700',
        'success' => 'bg-green-100 text-green-700',
        'danger' => 'bg-red-100 text-red-700',
        'warning' => 'bg-yellow-100 text-yellow-700',
        'info' => 'bg-blue-100 text-blue-700',
    ];
    
    $footerVariants = [
        'default' => 'bg-gray-50 border-t border-gray-200',
        'primary' => 'bg-indigo-50 border-t border-indigo-200',
        'success' => 'bg-green-50 border-t border-green-200',
        'danger' => 'bg-red-50 border-t border-red-200',
        'warning' => 'bg-yellow-50 border-t border-yellow-200',
        'info' => 'bg-blue-50 border-t border-blue-200',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg shadow-sm overflow-hidden border border-gray-200 mb-4 ' . $variants[$variant]]) }}>
    @if($title || $subtitle || $headerActions)
        <div class="px-4 py-3 {{ $headerVariants[$variant] }} border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    @if($title)
                        <h3 class="text-lg font-medium">{{ $title }}</h3>
                    @endif
                    
                    @if($subtitle)
                        <p class="text-sm text-gray-500">{{ $subtitle }}</p>
                    @endif
                </div>
                
                @if($headerActions)
                    <div>
                        {{ $headerActions }}
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    <div class="{{ $bodyClasses }}">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-4 py-3 {{ $footerVariants[$variant] }}">
            {{ $footer }}
        </div>
    @endif
</div> 