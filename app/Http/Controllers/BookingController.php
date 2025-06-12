<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\SpecialDate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar pemesanan milik pengguna yang sedang login.
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with('facility')
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan form untuk membuat pemesanan baru.
     */
    public function create()
    {
        // Hanya menampilkan fasilitas yang statusnya aktif
        $facilities = Facility::where('is_active', true)->get(); //
        return view('bookings.create', compact('facilities'));
    }

    /**
     * Menyimpan pemesanan baru ke database.
     */
    public function store(StoreBookingRequest $request)
    {
        // Validasi sekarang ditangani sepenuhnya oleh StoreBookingRequest
        Auth::user()->bookings()->create($request->validated()); //

        return redirect()->route('bookings.index')
            ->with('success', 'Pemesanan berhasil dibuat dan sedang menunggu persetujuan.');
    }

    /**
     * Menampilkan form untuk mengedit pemesanan.
     */
    public function edit(Booking $booking)
    {
        // Pastikan pengguna hanya bisa mengedit pemesanannya sendiri
        Gate::authorize('update', $booking);

        // Hanya boleh edit jika status masih 'pending'
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Pemesanan yang sudah diproses tidak dapat diubah.');
        }

        $facilities = Facility::where('is_active', true)->get();

        return view('bookings.edit', compact('booking', 'facilities'));
    }

    /**
     * Memperbarui pemesanan di database.
     */
    public function update(StoreBookingRequest $request, Booking $booking)
    {
        // Pastikan pengguna hanya bisa mengupdate pemesanannya sendiri
        Gate::authorize('update', $booking);
        
        // Hanya boleh update jika status masih 'pending'
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Pemesanan yang sudah diproses tidak dapat diubah.');
        }

        $booking->update($request->validated());

        return redirect()->route('bookings.index')
            ->with('success', 'Pemesanan berhasil diperbarui.');
    }

    /**
     * Membatalkan pemesanan.
     */
    public function destroy(Booking $booking)
    {
        // Pastikan pengguna hanya bisa membatalkan pemesanannya sendiri
        $this->authorize('delete', $booking);

        // Aturan tambahan: Hanya boleh batal jika status masih 'pending'
        if ($booking->status !== 'pending') { //
            return back()->with('error', 'Pemesanan yang sudah diproses tidak dapat dibatalkan.');
        }

        $booking->delete();

        return back()->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function checkAvailability(Request $request)
    {
        Log::info('Availability check request:', $request->all());

        try {
            $request->validate([
                'facility_id' => 'required|integer|exists:facilities,id',
                'date' => 'required|date_format:Y-m-d',
            ]);

            $facility = Facility::findOrFail($request->query('facility_id'));
            $date = $request->query('date');
            $carbonDate = Carbon::parse($date);
            Log::info('Facility found:', [$facility]);

            // Check if date is in past
            if ($carbonDate->isPast()) {
                return response()->json([
                    'error' => 'Tanggal tidak boleh di masa lalu'
                ], 400);
            }

            // Check facility availability for this day of week
            $dayOfWeek = $carbonDate->dayOfWeekIso;
            if (!$facility->isAvailableOnDay($dayOfWeek)) {
                return response()->json([
                    'error' => 'Fasilitas tidak beroperasi pada hari yang dipilih'
                ], 400);
            }

            // Check for special dates
            $specialDate = SpecialDate::where('facility_id', $facility->id)
                ->where('date', $date)
                ->first();

            // Determine operating hours
            $openingTime = $specialDate && !$specialDate->is_closed 
                ? Carbon::parse($specialDate->special_opening_time)
                : Carbon::parse($facility->opening_time);
                
            $closingTime = $specialDate && !$specialDate->is_closed
                ? Carbon::parse($specialDate->special_closing_time)
                : Carbon::parse($facility->closing_time);

            // Get all existing bookings
            $bookings = Booking::where('facility_id', $facility->id)
                ->where('booking_date', $date)
                ->whereIn('status', ['pending', 'approved'])
                ->get();

            // Prepare booked hours and slots
            $bookedSlots = [];
            foreach ($bookings as $booking) {
                $start = Carbon::parse($booking->start_time);
                $end = Carbon::parse($booking->end_time);
                
                $current = $start->copy();
                while ($current < $end) {
                    $bookedSlots[] = $current->format('H:i');
                    $current->addHour();
                }
            }

            return response()->json([
                'bookedSlots' => array_unique($bookedSlots),
                'operatingHours' => [
                    'opening' => $openingTime->format('H:i'),
                    'closing' => $closingTime->format('H:i')
                ],
                'specialDate' => $specialDate ? [
                    'is_closed' => $specialDate->is_closed,
                    'reason' => $specialDate->reason,
                    'special_hours' => !$specialDate->is_closed ? [
                        'opening' => $specialDate->special_opening_time,
                        'closing' => $specialDate->special_closing_time
                    ] : null
                ] : null,
                'max_booking_hours' => $facility->max_booking_hours,
                'facility_name' => $facility->name
            ]);

        } catch (\Exception $e) {
            Log::error('Availability check error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan saat memeriksa ketersediaan',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}