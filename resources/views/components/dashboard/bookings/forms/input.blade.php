@props(['type' => 'text', 'name', 'value' => '', 'required' => false, 'min' => null])

<input 
    type="{{ $type }}" 
    name="{{ $name }}" 
    value="{{ $value }}" 
    @if($required) required @endif
    @if($min) min="{{ $min }}" @endif
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
    ]) }} 
/>
