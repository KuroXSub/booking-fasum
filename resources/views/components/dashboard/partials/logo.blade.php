<a href="{{ route('dashboard') }}" class="flex items-center">
    <x-dashboard.application-logo class="h-8 w-auto text-indigo-600" />
    <span class="ml-2 text-xl font-semibold text-gray-900 hidden md:inline">
        {{ config('app.name') }}
    </span>
</a>