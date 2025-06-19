{{-- @props(['title', 'description', 'icon', 'href', 'buttonText', 'color' => 'indigo'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-{{ $color }}-100 dark:bg-{{ $color }}-900 p-3 rounded-lg">
                <x-dynamic-component :component="'icons.' . $icon" class="w-6 h-6 text-{{ $color }}-600 dark:text-{{ $color }}-400" />
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
            </div>
        </div>
    </div>
    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4">
        <a href="{{ $href }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-{{ $color }}-600 hover:bg-{{ $color }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $color }}-500">
            {{ $buttonText }}
        </a>
    </div>
</div> --}}