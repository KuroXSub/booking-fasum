<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::view('/about', 'about')->name('about');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::resource('bookings', BookingController::class)->except(['show']);

    Route::resource('bookings', BookingController::class)->names([
        'index' => 'bookings.index',
        'create' => 'bookings.create',
        'store' => 'bookings.store',
        'edit' => 'bookings.edit',
        'update' => 'bookings.update',
        'destroy' => 'bookings.destroy',
    ]);

    Route::get('/booking-availability', [BookingController::class, 'checkAvailability'])->name('booking.availability');;
});

require __DIR__.'/auth.php';
