@props(['title', 'description', 'icon', 'action'])

<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                <x-dynamic-component :component="'dashboard.ui.icons.' . $icon" class="h-6 w-6 text-white" />
            </div>
            <div class="ml-5 w-0 flex-1">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    {{ $title }}
                </dt>
                <dd class="flex items-baseline">
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $description }}
                    </p>
                </dd>
            </div>
        </div>
    </div>
    @if($action)
    <div class="bg-gray-50 px-5 py-3">
        <div class="text-sm">
            <a href="{{ $action['url'] }}" class="font-medium text-indigo-700 hover:text-indigo-900">
                {{ $action['text'] }}
            </a>
        </div>
    </div>
    @endif
</div>