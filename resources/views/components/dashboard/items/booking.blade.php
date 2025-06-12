@props(['booking'])

<div class="flex items-center justify-between p-4 hover:bg-gray-50">
    <div class="flex items-center min-w-0">
        <x-dashboard.ui.icons.calendar class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <div class="ml-4 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">
                {{ $booking->facility->name }}
            </p>
            <p class="text-sm text-gray-500 truncate">
                {{ $booking->booking_date->format('d M Y') }}
            </p>
        </div>
    </div>
    {{-- <x-dashboard.ui.badges.status :status="$booking->status" /> --}}
</div>