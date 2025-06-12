{{-- File: resources/views/components/dashboard/bookings/forms/time-select.blade.php --}}
@props(['name', 'required' => false])

<select 
    name="{{ $name }}" 
    @if($required) required @endif
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-base shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
    ]) }}>
    
    <option value="">-- Pilih Jam --</option>
    {{ $slot }}
</select>