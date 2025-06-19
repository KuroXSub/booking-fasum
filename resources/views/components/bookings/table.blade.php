@props(['bookings'])

<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    
                    {{-- Panggil komponen header --}}
                    <x-bookings.tables.table-header />

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                            <x-bookings.tables.table-row :booking="$booking" />
                        @empty
                            {{-- Panggil komponen empty state --}}
                            <x-bookings.tables.table-empty-state />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>