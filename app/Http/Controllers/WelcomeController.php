<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class WelcomeController extends Controller
{
    public function index()
    {
        $facilities = Facility::where('is_active', true)->get();
        return view('welcome', compact('facilities'));
    }
}
