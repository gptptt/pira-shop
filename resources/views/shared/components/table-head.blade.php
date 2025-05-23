@props(['sortable' => false, 'sorted' => null, 'align' => 'left'])

@php
    $classes = 'px-6 py-3 text-xs font-medium tracking-wider uppercase';

    if ($align === 'left') {
        $classes .= ' text-left';
    } elseif ($align === 'center') {
        $classes .= ' text-center';
    } elseif ($align === 'right') {
        $classes .= ' text-right';
    }

    if ($sortable) {
        $classes .= ' cursor-pointer hover:bg-gray-100';
    }

    $icon = 'fa-sort';
    $iconColor = 'text-gray-400';
    
    if ($sorted === 'asc') {
        $icon = 'fa-sort-up';
        $iconColor = 'text-indigo-600';
    } elseif ($sorted === 'desc') {
        $icon = 'fa-sort-down';
        $iconColor = 'text-indigo-600';
    }
@endphp

<th {{ $attributes->merge(['class' => $classes]) }} scope="col">
    <div class="flex items-center space-x-1">
        <span>{{ $slot }}</span>
        
        @if($sortable)
            <span class="{{ $iconColor }}">
                <i class="fas {{ $icon }}"></i>
            </span>
        @endif
    </div>
</th> 