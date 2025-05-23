@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => false, 'autofocus' => false, 'autocomplete' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    <input 
        id="{{ $name }}" 
        type="{{ $type }}" 
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error($name) border-red-500 @enderror" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        @if($required) required @endif 
        @if($autofocus) autofocus @endif 
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        {{ $attributes }}
    >
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @if(isset($hint))
        <p class="mt-1 text-sm text-gray-500">
            {{ $hint }}
        </p>
    @endif
</div> 