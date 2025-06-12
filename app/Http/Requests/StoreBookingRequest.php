<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\SpecialDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Validasi dasar
        return [
            'facility_id' => 'required|exists:facilities,id', //
            'booking_date' => 'required|date|after_or_equal:' . now()->addDay()->format('Y-m-d'), //
            'start_time' => 'required|date_format:H:00', //
            'end_time' => 'required|date_format:H:00|after:start_time', //
            'purpose' => 'required|string|max:255', //
        ];
    }

    /**
     * Get the custom validation messages.
     */
    public function messages(): array
    {
        return [
            'booking_date.after_or_equal' => 'Tanggal pemesanan hanya bisa dimulai dari besok (H-1).',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            // Ambil semua data yang sudah tervalidasi
            $data = $this->validated();

            // Cek apakah semua data penting ada sebelum melanjutkan
            if (empty($data['facility_id']) || empty($data['booking_date']) || empty($data['start_time']) || empty($data['end_time'])) {
                return;
            }

            $facility = Facility::find($data['facility_id']);
            $bookingDate = Carbon::parse($data['booking_date']);
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);

            // 1. Validasi Fasilitas Aktif
            if (!$facility || !$facility->is_active) { //
                $validator->errors()->add('facility_id', 'Fasilitas yang dipilih tidak tersedia.');
                return; // Hentikan validasi lebih lanjut jika fasilitas tidak valid
            }
            
            // Cek jadwal khusus
            $specialDate = SpecialDate::where('facility_id', $facility->id)->where('date', $bookingDate->format('Y-m-d'))->first(); //

            // 2. Validasi Hari Tersedia (Available Days & Special Dates)
            if ($specialDate && $specialDate->is_closed) { //
                $validator->errors()->add('booking_date', 'Fasilitas tutup pada tanggal yang dipilih. Alasan: ' . ($specialDate->reason ?? 'Hari Libur'));
                return;
            }
            if (!$specialDate && !$facility->isAvailableOnDay($bookingDate->dayOfWeekIso)) { //
                $validator->errors()->add('booking_date', 'Fasilitas tidak beroperasi pada hari yang dipilih.');
                return;
            }

            // Tentukan jam buka dan tutup (bisa dari jadwal khusus atau normal)
            $openingTime = Carbon::parse($specialDate?->special_opening_time ?? $facility->opening_time); //
            $closingTime = Carbon::parse($specialDate?->special_closing_time ?? $facility->closing_time); //

            // 3. Validasi Jam Operasional
            if ($startTime->isBefore($openingTime)) {
                $validator->errors()->add('start_time', "Jam mulai tidak boleh sebelum {$openingTime->format('H:i')}");
            }

            if ($endTime->isAfter($closingTime)) {
                $validator->errors()->add('end_time', "Jam selesai tidak boleh setelah {$closingTime->format('H:i')}");
            }

            if ($startTime->isAfter($closingTime->subHour())) {
                $validator->errors()->add('start_time', "Jam mulai tidak boleh setelah {$closingTime->format('H:i')}");
            }
            
            // 4. Validasi Durasi Maksimal Peminjaman
            $durationInHours = $startTime->diffInHours($endTime);
            if ($durationInHours > $facility->max_booking_hours) { //
                $validator->errors()->add('end_time', "Durasi pemesanan tidak boleh melebihi {$facility->max_booking_hours} jam.");
            }

            // 5. Validasi Bentrok Jadwal
            $clashExists = Booking::where('facility_id', $facility->id)
                ->where('booking_date', $bookingDate->format('Y-m-d'))
                ->whereIn('status', ['pending', 'approved']) //
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where('start_time', '<', $endTime->format('H:i:s'))
                          ->where('end_time', '>', $startTime->format('H:i:s'));
                })
                ->exists();

            if ($clashExists) {
                $validator->errors()->add('start_time', 'Jadwal pada jam ini sudah dipesan oleh pengguna lain.');
            }
        });
    }
}
