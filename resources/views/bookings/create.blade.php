<x-dashboard.layouts.app>

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto py-8">
        <div class="flex items-center space-x-3">
            <a href="{{ route('bookings.index') }}" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-o-arrow-left class="w-6 h-6" />
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Buat Pemesanan Baru</h1>
        </div>
        
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg">
            {{-- Mengirim data fasilitas sebagai prop dan atribut data HTML --}}
            <x-dashboard.bookings.form 
                :facilities="$facilities"
                :action="route('bookings.store')"
            />
        </div>
    </div>
</x-dashboard.layouts.app>

