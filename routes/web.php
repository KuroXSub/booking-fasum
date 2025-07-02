<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GoogleController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;



Route::view('/about', 'about')->name('about');

Route::prefix('auth/google')->controller(GoogleController::class)->group(function () {
    Route::get('/', 'redirectToGoogle')->name('auth.google');
    Route::get('callback', 'handleGoogleCallback');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
});

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::resource('bookings', BookingController::class)->except(['show']);

    Route::get('booking-availability', [BookingController::class, 'checkAvailability'])
        ->name('booking.availability');
});




Route::get('/', [WelcomeController::class, 'index'])->name('home');


require __DIR__.'/auth.php';
