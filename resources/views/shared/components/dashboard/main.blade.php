@props(['title' => 'Dashboard', 'subtitle' => null])

<div class="bg-gray-50 min-h-screen">
    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-4 mb-6 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="mt-4 md:mt-0 space-x-2 flex flex-wrap">
                    {{ $actions }}
                </div>
            @endif
        </div>

        @if(isset($stats))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                {{ $stats }}
            </div>
        @endif

        @if(isset($filters))
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                {{ $filters }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            @if(isset($primary))
                <div class="lg:col-span-8 space-y-6">
                    {{ $primary }}
                </div>
            @endif

            @if(isset($secondary))
                <div class="lg:col-span-4 space-y-6">
                    {{ $secondary }}
                </div>
            @endif
        </div>

        @if(isset($content))
            <div class="mt-6">
                {{ $content }}
            </div>
        @endif
    </div>
</div> 