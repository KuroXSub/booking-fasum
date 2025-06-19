<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Berikan semua akses kepada admin tanpa perlu cek method lain.
     *
     * @param \App\Models\User $user
     * @param string $ability
     * @return bool|null
     */
    public function before(User $user, $ability)
    {
        // Ganti 'is_admin' dengan cara Anda mengidentifikasi admin, 
        // misalnya $user->hasRole('admin') jika menggunakan Spatie/laravel-permission
        if ($user->is_admin) { 
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua pengguna yang terautentikasi bisa melihat daftar pemesanan mereka sendiri.
        // Admin sudah diizinkan oleh method before().
        return true; 
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        // Pengguna bisa melihat detail pemesanan miliknya.
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Semua pengguna yang terautentikasi bisa membuat pemesanan.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        // Pengguna bisa mengupdate pemesanan miliknya.
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Pengguna bisa menghapus pemesanan miliknya.
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        // Secara default, hanya admin yang bisa (ditangani oleh before).
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        // Secara default, hanya admin yang bisa (ditangani oleh before).
        return false;
    }
}