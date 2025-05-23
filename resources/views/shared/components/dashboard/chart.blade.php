@props(['id', 'title', 'type' => 'line', 'height' => '300'])

<x-card :title="$title">
    <div style="height: {{ $height }}px;">
        <canvas id="{{ $id }}"></canvas>
    </div>
    
    @if(isset($footer))
        <x-slot:footer>
            {{ $footer }}
        </x-slot:footer>
    @endif
</x-card>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('{{ $id }}');
        
        @if(isset($chartData))
            new Chart(ctx, {!! $chartData !!});
        @else
            // If no chart data was provided, we'll use the slot content
            {{ $slot }}
        @endif
    });
</script>
@endpush 