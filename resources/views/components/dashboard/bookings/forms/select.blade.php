@props(['name', 'options' => [], 'selected' => null, 'required' => false])

<select 
    name="{{ $name }}" 
    @if($required) required @endif
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
    ]) }}>
    
    {{ $slot }}
    
    @foreach($options as $key => $value)
        <option value="{{ $key }}" {{ ($key == $selected) ? 'selected' : '' }}>
            {{ $value }}
        </option>
    @endforeach
</select>
