{{-- File: resources/views/bookings/index.blade.php --}}
<x-users.layouts.app>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <div class="flex items-center space-x-3">
                <x-users.ui.icons.calendar class="w-8 h-8 text-gray-500" />
                <h1 class="text-2xl font-bold text-gray-800">Peminjaman Saya</h1>
            </div>
            
            <a href="{{ route('bookings.create') }}" class="mt-4 sm:mt-0 inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Buat Peminjaman Baru
            </a>
        </div>

        <x-users.ui.alerts />

        <x-bookings.table :bookings="$bookings" /> 
    </div>
</x-users.layouts.app>