@props(['user'])

<div class="flex items-center px-4 py-3 border-b border-gray-200">
    <img class="h-10 w-10 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
    <div class="ml-4">
        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
        <p class="text-sm text-gray-500">{{ $user->email }}</p>
    </div>
</div>