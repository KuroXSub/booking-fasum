@props(['bookings'])

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="text-lg font-medium">Recent Bookings</h3>
        <a href="{{ route('bookings.index') }}" class="text-sm text-indigo-600 hover:underline">
            View All
        </a>
    </div>
    <div class="divide-y divide-gray-200">
        @forelse($bookings as $booking)
            <x-dashboard.items.booking :booking="$booking" />
        @empty
            <div class="p-6 text-center text-gray-500">
                No bookings yet. 
                <a href="{{ route('bookings.create') }}" class="text-indigo-600 hover:underline">
                    Create your first booking
                </a>
            </div>
        @endforelse
    </div>
</div>