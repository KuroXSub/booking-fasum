@props(['booking'])

<div class="px-6 py-4 hover:bg-gray-50 transition">
    <div class="flex items-center justify-between">
        <div>
            <p class="font-medium">{{ $booking->facility->name }}</p>
            <p class="text-sm text-gray-500">
                {{ $booking->start_date->format('M d, Y') }}
                @if($booking->start_time)
                    • {{ $booking->start_time->format('H:i') }}
                @endif
            </p>
        </div>
        <span @class([
            'px-2 py-1 text-xs rounded-full',
            'bg-green-100 text-green-800' => $booking->status === 'approved',
            'bg-yellow-100 text-yellow-800' => $booking->status === 'pending',
            'bg-red-100 text-red-800' => $booking->status === 'rejected'
        ])>
            {{ ucfirst($booking->status) }}
        </span>
    </div>
</div>