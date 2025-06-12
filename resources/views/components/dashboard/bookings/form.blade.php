{{-- File: resources/views/components/dashboard/bookings/form.blade.php --}}
@props([
    'facilities', 
    'booking' => null,
    'method' => 'POST',
    'action'
])

@php
$bookingData = null;
if ($booking) {
    $bookingData = [
        'facility_id' => $booking->facility_id,
        'booking_date' => $booking->booking_date->format('Y-m-d'), // Memastikan format tanggal YYYY-MM-DD
        'start_time' => \Carbon\Carbon::parse($booking->start_time)->format('H:i'),
        'end_time' => \Carbon\Carbon::parse($booking->end_time)->format('H:i'),
        'purpose' => $booking->purpose,
    ];
}
@endphp

<div x-data="bookingForm({
    facilities: {{ $facilities->map->only(['id', 'name', 'available_days', 'opening_time', 'closing_time', 'max_booking_hours'])->values() }},
    initialBooking: @json($bookingData),
    availabilityUrl: '{{ route('booking.availability') }}'
})">
    <form method="POST" action="{{ $action }}" class="space-y-8">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif
        
        <x-dashboard.ui.validation-errors />

        {{-- Step 1: Facility Selection --}}
        <div class="space-y-2">
            <x-dashboard.bookings.forms.label for="facility_id">
                <span class="text-lg font-semibold text-gray-800">1. Pilih Fasilitas</span>
            </x-dashboard.bookings.forms.label>
            <select x-model="selectedFacilityId" 
                    @change="updateFacilityDetails()" 
                    name="facility_id" 
                    id="facility_id" 
                    required 
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-base shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">-- Pilih salah satu fasilitas --</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Show next steps only if facility is selected --}}
        <template x-if="selectedFacility.id">
            <div class="space-y-8 border-t border-gray-200 pt-8">
                {{-- Step 2: Enhanced Date Selection --}}
                <div class="space-y-2">
                    <x-dashboard.bookings.forms.label for="booking_date">
                        <span class="text-lg font-semibold text-gray-800">2. Pilih Tanggal</span>
                    </x-dashboard.bookings.forms.label>
                    
                    <div class="relative">
                        <input type="text" 
                            x-ref="dateInput"
                            x-model="bookingDate"
                            @click="initDatepicker()"
                            @change="generateAvailableHours()"
                            name="booking_date" 
                            id="booking_date"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-base shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer"
                            placeholder="Klik untuk memilih tanggal"
                            readonly>
                    </div>
                    
                    <div x-show="specialDateInfo" class="mt-2 p-3 rounded-lg" 
                        :class="specialDate.is_closed ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700'">
                        <p x-html="specialDateInfo"></p>
                        <template x-if="specialDate && !specialDate.is_closed">
                            <p class="mt-1 font-medium">
                                Jam operasional khusus: 
                                <span x-text="formatTime(specialDate.special_opening_time)"></span> - 
                                <span x-text="formatTime(specialDate.special_closing_time)"></span>
                            </p>
                        </template>
                    </div>
                    
                    <p class="text-sm text-gray-500" x-text="dateHelperText"></p>

                    <div x-show="validationMessages.date" class="mt-2 p-2 rounded text-sm bg-red-50 text-red-700">
                        <p x-text="validationMessages.date"></p>
                    </div>

                    <div x-show="isCheckingAvailability" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-4 rounded-lg shadow-lg">
                            <p class="text-center">Memeriksa ketersediaan...</p>
                            <div class="flex justify-center mt-2">
                                <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Show next steps only if valid date is selected --}}
                <template x-if="isDateValid()">
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Start Time --}}
                            <div class="space-y-2">
                                <x-dashboard.bookings.forms.label for="start_time">
                                    <span class="text-lg font-semibold text-gray-800">3. Jam Mulai</span>
                                    <span x-show="currentOpeningTime" class="text-sm font-normal text-gray-500">
                                        (Buka: <span x-text="currentOpeningTime"></span>)
                                    </span>
                                </x-dashboard.bookings.forms.label>
                                
                                <div class="relative">
                                    <input type="text"
                                        x-model="startTime"
                                        name="start_time"
                                        id="start_time"
                                        required
                                        readonly
                                        placeholder="Pilih slot waktu di bawah"
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-base shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-default">
                                </div>
                                
                                {{-- Real-time Availability Feedback --}}
                                <div x-show="timeFeedback.startTime" class="mt-2 p-2 rounded text-sm"
                                    :class="timeFeedback.startTime.type === 'error' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'">
                                    <p x-text="timeFeedback.startTime.message"></p>
                                </div>
                                
                                {{-- Available Time Slots --}}
                                <div x-show="availableStartHours.length > 0" class="mt-2">
                                    <p class="text-sm font-medium text-gray-700 mb-1">Slot waktu tersedia:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="hour in availableStartHours" :key="hour.value">
                                            <span @click="selectStartTime(hour.value)"
                                                class="px-3 py-1 rounded-full text-xs font-medium cursor-pointer"
                                                :class="{
                                                    'bg-green-100 text-green-800 hover:bg-green-200': !hour.disabled,
                                                    'bg-red-100 text-red-800 line-through': hour.disabled,
                                                    'bg-indigo-100 text-indigo-800': hour.value === startTime && !hour.disabled
                                                }"
                                                x-text="hour.label + (hour.disabled ? ' (Penuh)' : '')">
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- End Time --}}
                            <div class="space-y-2">
                                <x-dashboard.bookings.forms.label for="end_time">
                                    <span class="text-lg font-semibold text-gray-800">4. Jam Selesai</span>
                                    <span x-show="currentClosingTime" class="text-sm font-normal text-gray-500">
                                        (Tutup: <span x-text="currentClosingTime"></span>)
                                    </span>
                                </x-dashboard.bookings.forms.label>
                                
                                <div class="relative">
                                    <input type="time"
                                        x-model="endTime"
                                        @change="validateEndTime()"
                                        name="end_time"
                                        id="end_time"
                                        required
                                        :min="startTime ? getNextHour(startTime) : ''"
                                        :max="getMaxEndTime()"
                                        step="3600"
                                        :disabled="!startTime"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-base shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                </div>
                                
                                {{-- Real-time Duration Feedback --}}
                                <div x-show="timeFeedback.endTime" class="mt-2 p-2 rounded text-sm"
                                    :class="timeFeedback.endTime.type === 'error' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'">
                                    <p x-text="timeFeedback.endTime.message"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Step 5: Purpose --}}
                        <div class="space-y-2">
                            <x-dashboard.bookings.forms.label for="purpose">
                                <span class="text-lg font-semibold text-gray-800">5. Tujuan Peminjaman</span>
                            </x-dashboard.bookings.forms.label>
                            <x-dashboard.bookings.forms.textarea 
                                x-model="purpose" 
                                name="purpose" 
                                id="purpose" 
                                rows="4" 
                                required 
                                placeholder="Contoh: Rapat Karang Taruna bulanan"></x-dashboard.bookings.forms.textarea>
                        </div>
                    </div>
                </template>
            </div>
        </template>
        
        <div class="flex justify-end space-x-4 border-t border-gray-200 pt-6">
            <a href="{{ route('bookings.index') }}" class="px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" 
                    :disabled="!isFormComplete"
                    class="px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                {{ $booking ? 'Simpan Perubahan' : 'Buat Pesanan' }}
            </button>
        </div>
    </form>
</div>