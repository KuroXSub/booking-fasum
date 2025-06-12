{{-- File: resources/views/components/dashboard/bookings/table.blade.php --}}
@props(['bookings'])

<div class="overflow-x-auto bg-white rounded-lg shadow-md">
    <table class="min-w-full divide-y divide-gray-200"> 
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th> 
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Aksi</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($bookings as $booking)
                {{-- BARU: Memanggil komponen table-row untuk setiap baris --}}
                <x-dashboard.bookings.table-row :booking="$booking" />
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Anda belum memiliki pemesanan.
                    </td> 
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($bookings->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $bookings->links() }}
    </div>
    @endif
</div>