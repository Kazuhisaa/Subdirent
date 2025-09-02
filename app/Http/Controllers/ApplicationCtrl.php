<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationCtrl extends Controller
{
    public function submit(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'unit' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        return back()->with('success', 'Application submitted successfully for ' . $data['unit']);
    }
}
