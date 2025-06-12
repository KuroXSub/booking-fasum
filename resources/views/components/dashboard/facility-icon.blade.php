@props(['facility'])

@php
    $icons = [
        'venue' => 'building-office',
        'equipment' => 'cube',
    ];
@endphp

<x-dynamic-component :component="'dashboard.icons.' . ($icons[$facility->category] ?? 'cube')" 
                     class="h-10 w-10 text-gray-400" />