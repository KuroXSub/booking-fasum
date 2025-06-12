{{-- File: resources/views/components/dashboard/bookings/table-row.blade.php --}}
@props(['booking'])

{{-- x-data mendefinisikan state untuk Alpine.js --}}
<tr x-data="{ confirmingCancel: false }" class="hover:bg-gray-50 transition-colors duration-200">
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
        <x-dashboard.bookings.status-badge :status="$booking->status" /> 
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        @if($booking->status === 'pending')
            <div class="flex items-center justify-end space-x-4">
                {{-- TOMBOL EDIT BARU --}}
                <a href="{{ route('bookings.edit', $booking) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors duration-200">
                    Edit
                </a>
                
                {{-- Tombol Batalkan yang sudah ada --}}
                <button @click="confirmingCancel = true" class="text-red-600 hover:text-red-800 font-semibold transition-colors duration-200">
                    Batalkan
                </button>
            </div>
        @else
            <span class="text-xs text-gray-400 italic">Tidak ada aksi</span>
        @endif
    </td>

    {{-- BARU: Dialog Konfirmasi Pembatalan (Modal) dengan Alpine.js --}}
    <template x-if="confirmingCancel">
        <div class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div @click.away="confirmingCancel = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-90" 
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90">
                
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Pembatalan</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Apakah Anda yakin ingin membatalkan pemesanan untuk **{{ $booking->facility->name }}** pada tanggal **{{ $booking->booking_date->format('d M Y') }}**?
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="confirmingCancel = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Tidak, Jangan Batalkan
                    </button>
                    {{-- Form ini akan disubmit jika user mengklik "Ya, Batalkan" --}}
                    <form method="POST" action="{{ route('bookings.destroy', $booking) }}"> 
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            Ya, Batalkan
                        </button> 
                    </form>
                </div>
            </div>
        </div>
    </template>
</tr>