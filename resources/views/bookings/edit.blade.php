<x-users.layouts.app>

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Edit Booking</h1>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <x-bookings.form 
            :booking="$booking"
            :facilities="$facilities"
            method="PUT"
            action="{{ route('bookings.update', $booking) }}" 
        />
    </div>
</div>
</x-users.layouts.app>