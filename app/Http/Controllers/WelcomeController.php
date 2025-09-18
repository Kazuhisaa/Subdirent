<?php

namespace App\Http\Controllers;

use App\Models\Unit;

class WelcomeController extends Controller
{
    public function index()
    {
        $units = Unit::where('status', 'Available')->get();
        return view('welcome', compact('units'));
    }
}
