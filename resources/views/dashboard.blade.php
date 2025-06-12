
<x-dashboard.layouts.app>
    <x-slot name="header">
        <x-dashboard.sections.header 
            title="Welcome back, {{ Auth::user()->name }}!" 
            subtitle="Here's what's happening with your bookings" />
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Section -->
        <x-dashboard.sections.stats :stats="[
            [
                'title' => 'Total Bookings',
                'value' => $stats['total_bookings'],
                'icon' => 'calendar',
                'color' => 'blue'
            ],
            [
                'title' => 'Approved',
                'value' => $stats['approved'],
                'icon' => 'check-circle',
                'color' => 'green'
            ],
            [
                'title' => 'Pending',
                'value' => $stats['pending'],
                'icon' => 'clock',
                'color' => 'yellow'
            ]
        ]" />

        <!-- Recent Bookings Section -->
        <x-dashboard.sections.recent-bookings :bookings="$recentBookings" />
        
        <!-- Quick Actions Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-dashboard.cards.info 
                title="New Booking" 
                description="Create a new facility booking"
                icon="plus"
                :action="['url' => route('bookings.create'), 'text' => 'Create Now']" />
                
            <x-dashboard.cards.info 
                title="My Facilities" 
                description="View your booking history"
                icon="calendar"
                :action="['url' => route('bookings.index'), 'text' => 'View All']" />
        </div>
    </div>
</x-dashboard.layouts.app>