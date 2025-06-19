{{-- File: resources/views/components/dashboard/bookings/table-row.blade.php --}}
@props(['booking'])

{{-- x-data mendefinisikan state untuk Alpine.js --}}
<tr x-data="{ modalOpen: false }" class="border-b border-gray-200 hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
                {{-- Menampilkan gambar fasilitas jika ada --}}
                <img class="h-10 w-10 rounded-full object-cover" src="{{ $booking->facility->image ? asset('storage/' . $booking->facility->image) : 'https://ui-avatars.com/api/?name=' . urlencode($booking->facility->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ $booking->facility->name }}">
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ $booking->facility->name }}</div> 
                <div class="text-sm text-gray-500">{{ $booking->facility->category->name }}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        {{-- DIUBAH: Menggunakan booking_date, start_time, dan end_time --}}
        <div class="text-sm font-medium text-gray-900">
            {{ $booking->booking_date->format('d M Y') }}
        </div>
        <div class="text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} 
        </div>
    </td>
    <td class="px-6 py-4 whitespace-normal">
        <div class="text-sm text-gray-900 max-w-xs break-words">
            {{ $booking->purpose }} 
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <x-bookings.status-badge :status="$booking->status" /> 
    </td>
    {{-- Kolom Aksi (Tombol) --}}
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative">
        @if($booking->status === 'pending')
            <a href="{{ route('bookings.edit', $booking) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
            <span class="mx-2 text-gray-300">|</span>
            {{-- Tombol ini akan mengubah state modalOpen menjadi true --}}
            <button @click="modalOpen = true" type="button" class="text-red-600 hover:text-red-900">
                Batalkan
            </button>
        @else
            <span class="text-gray-400 italic">Tidak ada aksi</span>
        @endif

        {{-- Modal ini sekarang berada dalam lingkup x-data dan akan muncul saat modalOpen = true --}}
        <div x-show="modalOpen" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-white/30 backdrop-blur-sm">
            
            <div @click.away="modalOpen = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-medium text-gray-900 text-left">Konfirmasi Pembatalan</h3>
                <p class="mt-2 text-sm text-gray-600 text-left break-words whitespace-normal">
                    Apakah Anda yakin ingin membatalkan pemesanan untuk <strong>{{ $booking->facility->name }}</strong> pada tanggal <strong>{{ $booking->booking_date->translatedFormat('d F Y') }}</strong>?
                </p>
                <div class="mt-4 flex justify-end space-x-3">
                    <button @click="modalOpen = false" type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Tidak
                    </button>
                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700">
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </td>
</tr>