<x-users.layouts.app>
    <x-slot name="header">
        <x-dashboard.sections.header 
            title="Selamat datang kembali, {{ Auth::user()->name }}!" 
            subtitle="Pinjamlah Fasilitas Desa Dengan Bijak." />
    </x-slot>

    <div class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-dashboard.cards.info 
                title="Peminjaman Baru" 
                description="Ajukan peminjaman fasilitas baru"
                icon="plus"
                :action="['url' => route('bookings.create'), 'text' => 'Buat Sekarang']" />

            <x-dashboard.cards.info 
                title="Riwayat Peminjaman" 
                description="Lihat daftar peminjaman Anda"
                icon="calendar"
                :action="['url' => route('bookings.index'), 'text' => 'Lihat Semua']" />
        </div>

        <x-dashboard.sections.stats :stats="[
            [
                'title' => 'Total Peminjaman',
                'value' => $stats['total_bookings'],
                'icon' => 'calendar',
                'color' => 'blue'
            ],
            [
                'title' => 'Disetujui',
                'value' => $stats['approved'],
                'icon' => 'check-circle',
                'color' => 'green'
            ],
            [
                'title' => 'Menunggu',
                'value' => $stats['pending'],
                'icon' => 'clock',
                'color' => 'yellow'
            ]
        ]" />

        <x-dashboard.sections.recent-bookings :bookings="$recentBookings" />

    </div>
</x-users.layouts.app>
