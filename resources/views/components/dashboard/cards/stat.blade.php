@props(['title', 'value', 'icon', 'color' => 'blue'])

<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex items-center">
        <div class="p-3 rounded-full bg-{{ $color }}-100 text-{{ $color }}-600">
            <x-dynamic-component 
                :component="'users.ui.icons.' . $icon" 
                class="h-6 w-6" />
        </div>
        <div class="ml-4">
            <p class="text-sm font-bold text-gray-500">{{ $title }}</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>