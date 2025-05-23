@props(['name', 'label', 'checked' => false, 'value' => '1', 'description' => null])

<div class="mb-4">
    <div class="flex items-center">
        <label class="inline-flex items-center cursor-pointer">
            <input 
                type="checkbox" 
                id="{{ $name }}" 
                name="{{ $name }}" 
                value="{{ $value }}" 
                {{ old($name, $checked) ? 'checked' : '' }}
                {{ $attributes }}
                class="sr-only peer"
            >
            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
            <span class="ml-3 text-sm font-medium text-gray-700">{{ $label }}</span>
        </label>
    </div>
    @if($description)
        <p class="mt-1 text-sm text-gray-500 ml-14">{{ $description }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-sm text-red-600 ml-14">{{ $message }}</p>
    @enderror
</div> 