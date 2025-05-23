@props([
    'type' => 'info',
    'dismissible' => false,
    'icon' => null
])

@php
    $types = [
        'info' => [
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-700',
            'border' => 'border-blue-400',
            'icon' => $icon ?? 'fas fa-info-circle',
            'close-bg' => 'bg-blue-50 hover:bg-blue-100'
        ],
        'success' => [
            'bg' => 'bg-green-50',
            'text' => 'text-green-700',
            'border' => 'border-green-400',
            'icon' => $icon ?? 'fas fa-check-circle',
            'close-bg' => 'bg-green-50 hover:bg-green-100'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'text' => 'text-yellow-700',
            'border' => 'border-yellow-400',
            'icon' => $icon ?? 'fas fa-exclamation-triangle',
            'close-bg' => 'bg-yellow-50 hover:bg-yellow-100'
        ],
        'danger' => [
            'bg' => 'bg-red-50',
            'text' => 'text-red-700',
            'border' => 'border-red-400',
            'icon' => $icon ?? 'fas fa-exclamation-circle',
            'close-bg' => 'bg-red-50 hover:bg-red-100'
        ],
    ];
    
    $alertType = $types[$type] ?? $types['info'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-md p-4 ' . $alertType['bg'] . ' ' . $alertType['border'] . ' border-l-4 mb-4']) }}
    role="alert"
    x-data="{ open: true }"
    x-show="open"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="{{ $alertType['icon'] }} {{ $alertType['text'] }}"></i>
        </div>
        <div class="ml-3 flex-1 {{ $alertType['text'] }}">
            {{ $slot }}
        </div>
        
        @if($dismissible)
        <div class="ml-auto pl-3">
            <div class="-my-1.5">
                <button @click="open = false" type="button" class="{{ $alertType['close-bg'] }} inline-flex rounded-md p-1.5 {{ $alertType['text'] }} hover:{{ $alertType['text'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ $type }}-50 focus:ring-{{ $type }}-500">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>
</div> 