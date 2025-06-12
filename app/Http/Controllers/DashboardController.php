<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        
        return view('dashboard', [
            'stats' => [
                'total_bookings' => $user->bookings()->count(),
                'approved' => $user->bookings()->where('status', 'approved')->count(),
                'pending' => $user->bookings()->where('status', 'pending')->count()
            ],
            'recentBookings' => $user->bookings()
                ->with('facility')
                ->latest()
                ->take(5)
                ->get()
        ]);
    }
}