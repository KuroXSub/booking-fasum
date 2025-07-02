<x-users.layouts.app>

@section('content')
<div class="space-y-6 max-w-4xl mx-auto py-8">
    <div class="flex items-center space-x-3">
            <a href="{{ route('bookings.index') }}" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-o-arrow-left class="w-6 h-6" />
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Edit Booking</h1>
        </div>
    
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg">
        <x-bookings.form 
            :booking="$booking"
            :facilities="$facilities"
            method="PUT"
            action="{{ route('bookings.update', $booking) }}" 
        />
    </div>
</div>
</x-users.layouts.app>