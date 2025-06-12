<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Facility;
use App\Models\SpecialDate;

// Route untuk mendapatkan daftar tanggal tutup (libur) suatu fasilitas
Route::get('/facilities/{facility}/closed-dates', function (Facility $facility) {
    $closedDates = SpecialDate::where('facility_id', $facility->id)
        ->where('is_closed', true)
        ->pluck('date')
        ->map(function ($date) {
            // Pastikan formatnya Y-m-d agar sesuai dengan Flatpickr
            return \Illuminate\Support\Carbon::parse($date)->format('Y-m-d');
        });

    return response()->json($closedDates);
})->middleware('auth'); // Lindungi route ini agar hanya bisa diakses user terotentikasi