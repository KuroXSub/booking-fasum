@props(['name', 'value' => '', 'rows' => 3])

<textarea 
    name="{{ $name }}" 
    rows="{{ $rows }}" 
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
    ]) }}>{{ $value }}</textarea>
