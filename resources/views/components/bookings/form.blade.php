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
        'id' => $booking->id,
        'facility_id' => $booking->facility_id,
        'booking_date' => $booking->booking_date->format('Y-m-d'),
        'start_time' => \Carbon\Carbon::parse($booking->start_time)->format('H:i'),
        'end_time' => \Carbon\Carbon::parse($booking->end_time)->format('H:i'),
        'purpose' => $booking->purpose,
    ];
}
@endphp

<div x-data="bookingForm({
    facilities: {{ $facilities->map->only(['id', 'name', 'available_days', 'opening_time', 'closing_time', 'max_booking_hours'])->values() }},
    initialBooking: {{ $bookingData ? json_encode($bookingData) : 'null' }},
    availabilityUrl: '{{ route('booking.availability') }}'
})" x-init="
    if (initialBooking) {
        selectedFacilityId = initialBooking.facility_id;
        $nextTick(() => {
            updateFacilityDetails();
        });
    }
">
    <form method="POST" action="{{ $action }}" class="space-y-8">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif
        
        <x-users.ui.validation-errors />

        {{-- Step 1: Facility Selection --}}
        <div class="space-y-2">
            <x-bookings.forms.label for="facility_id">
                <span class="text-lg font-semibold text-gray-800">1. Pilih Fasilitas</span>
            </x-bookings.forms.label>
            <select x-model="selectedFacilityId" 
                    @change="updateFacilityDetails()" 
                    name="facility_id" 
                    id="facility_id" 
                    required 
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-base shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">-- Pilih salah satu fasilitas --</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" :selected="selectedFacilityId == {{ $facility->id }}">{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tampilkan langkah selanjutnya jika fasilitas dipilih --}}
        <template x-if="selectedFacility.id">
            <div class="space-y-8 border-t border-gray-200 pt-8">

                {{-- Step 2: Date Selection --}}
                <div class="space-y-2">
                    <x-bookings.forms.label for="booking_date">
                        <span class="text-lg font-semibold text-gray-800">2. Pilih Tanggal</span>
                    </x-bookings.forms.label>
                    
                    <div class="relative">
                        <input type="text" 
                            x-ref="dateInput"
                            x-model="bookingDate"
                            @click="initDatepicker()"
                            name="booking_date" 
                            id="booking_date"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-base shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer"
                            placeholder="Klik untuk memilih tanggal"
                            readonly>
                    </div>
                    
                    <div x-show="specialDateInfo" class="mt-2 p-3 rounded-lg" 
                        :class="validationMessages.date && validationMessages.date.includes('tutup') ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700'">
                        <p x-html="specialDateInfo"></p>
                    </div>
                    
                    <p class="text-sm text-gray-500" x-text="dateHelperText"></p>
                </div>

                {{-- Tampilkan langkah selanjutnya jika tanggal valid --}}
                <template x-if="isDateValid() && !validationMessages.date">
                    <div class="space-y-8">

                        {{-- DIUBAH: Layout diubah dari grid 2 kolom menjadi tumpukan vertikal --}}
                        <div class="space-y-8">
                            
                            {{-- BAGIAN JAM MULAI --}}
                            <div class="space-y-4">
                                {{-- Step 3: Start Time --}}
                                <div class="space-y-2">
                                    <x-bookings.forms.label for="start_time">
                                        <span class="text-lg font-semibold text-gray-800">3. Pilih Jam Mulai</span>
                                        <span x-show="currentOpeningTime" class="text-sm font-normal text-gray-500">
                                            (Buka: <span x-text="currentOpeningTime"></span>)
                                        </span>
                                    </x-bookings.forms.label>
                                    
                                    <input type="text"
                                        x-model="startTime"
                                        name="start_time"
                                        id="start_time"
                                        required
                                        readonly
                                        placeholder="Pilih slot waktu di bawah"
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-base shadow-sm cursor-default">
                                    
                                    <x-bookings.forms.feedback-box feedback="timeFeedback.startTime" />
                                </div>
                                
                                {{-- Tombol-tombol Slot Waktu --}}
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                                    <template x-for="hour in availableStartHours" :key="hour.value">
                                        <button 
                                            type="button"
                                            @click="selectStartTime(hour.value)"
                                            :disabled="hour.disabled"
                                            :class="{
                                                'bg-indigo-600 text-white hover:bg-indigo-700 ring-2 ring-offset-2 ring-indigo-500': startTime === hour.value,
                                                'bg-white hover:bg-gray-100 text-gray-800': startTime !== hour.value,
                                                'opacity-50 cursor-not-allowed bg-gray-100 text-gray-400': hour.disabled
                                            }"
                                            class="w-full text-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <span x-text="hour.label"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            
                            {{-- BAGIAN DURASI (AKAN TAMPIL DI BAWAH JAM MULAI) --}}
                            <div class="space-y-4" x-show="startTime" x-transition:enter.duration.300ms>
                                {{-- Step 4: Duration Selection --}}
                                <div class="space-y-2">
                                    <x-bookings.forms.label>
                                        <span class="text-lg font-semibold text-gray-800">4. Pilih Durasi</span>
                                         <span x-show="currentClosingTime" class="text-sm font-normal text-gray-500">
                                            (Maks. <span x-text="selectedFacility.max_booking_hours"></span> jam, Tutup: <span x-text="currentClosingTime"></span>)
                                        </span>
                                    </x-bookings.forms.label>
                                    
                                    <input type="hidden" name="end_time" :value="endTime">

                                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                                        <template x-if="availableDurations.length > 0">
                                            <template x-for="option in availableDurations" :key="option.duration">
                                                <button
                                                    type="button"
                                                    @click="selectDuration(option.duration)"
                                                    :class="{
                                                        'bg-indigo-600 text-white hover:bg-indigo-700 ring-2 ring-offset-2 ring-indigo-500': selectedDuration === option.duration,
                                                        'bg-white hover:bg-gray-100 text-gray-800': selectedDuration !== option.duration
                                                    }"
                                                    class="w-full text-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <span x-text="option.label"></span>
                                                </button>
                                            </template>
                                        </template>
                                        <template x-if="startTime && availableDurations.length === 0">
                                            <p class="text-sm text-gray-500 col-span-full">Tidak ada durasi yang tersedia dari jam ini.</p>
                                        </template>
                                    </div>

                                     <div x-show="endTime" class="mt-2">
                                        <x-bookings.forms.feedback-box feedback="timeFeedback.endTime" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 5: Purpose --}}
                        <div class="space-y-2 pt-4" x-show="startTime && endTime" x-transition>
                            <x-bookings.forms.label for="purpose">
                                <span class="text-lg font-semibold text-gray-800">5. Tujuan Peminjaman</span>
                            </x-bookings.forms.label>
                            
                            <x-bookings.forms.textarea 
                                x-model="purpose" 
                                @input.debounce.300ms="validatePurpose()"
                                name="purpose" 
                                id="purpose" 
                                rows="4" 
                                required 
                                placeholder="Contoh: Rapat rutin Karang Taruna RW 05."></x-bookings.forms.textarea>

                            <template x-if="purposeError">
                                <p x-text="purposeError" class="text-sm text-red-600"></p>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </template>
        
        {{-- Tombol Aksi --}}
        <div class="flex justify-end space-x-4 border-t border-gray-200 pt-6">
            <a href="{{ route('bookings.index') }}" class="px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Batal
            </a>
            <button type="submit" 
                    :disabled="!isFormComplete"
                    class="px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 disabled:bg-gray-400 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800">
                {{ $booking ? 'Simpan Perubahan' : 'Buat Pesanan' }}
            </button>
        </div>
    </form>
</div>