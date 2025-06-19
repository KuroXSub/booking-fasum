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
        return [
            'facility_id' => 'required|exists:facilities,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $bookingDate = $this->input('booking_date');
                    $endTime = $this->input('end_time');
                    $facilityId = $this->input('facility_id');

                    if (!$bookingDate || !$endTime || !$facilityId) {
                        return;
                    }

                    $query = Booking::where('facility_id', $facilityId)
                        ->where('booking_date', $bookingDate)
                        ->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $value);

                    if ($this->isMethod('put') || $this->isMethod('patch')) {
                        $currentBookingId = $this->route('booking')->id;
                        $query->where('id', '!=', $currentBookingId);
                    }

                    if ($query->exists()) {
                        $fail('Jadwal pada jam ini sudah dipesan oleh pengguna lain.');
                    }
                },
            ],
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|min:10|max:255',
        ];
    }
}
