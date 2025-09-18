<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Unit;

class ApplicationController extends Controller
{
    public function submit(Request $request, $unitId)
    {
        // Validate input
        $data = $request->validate([
            'surname'       => 'required|string|max:255',
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'email'         => 'required|email',
            'contact_number'=> 'required|string|max:20',
            'id_file'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'salary'        => 'nullable|numeric',
        ]);

        $unit = Unit::findOrFail($unitId);

        if ($unit->occupied) {
            return back()->with('error', 'This unit is already occupied.');
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('id_file')) {
            $filePath = $request->file('id_file')->store('ids', 'public');
        }

        // Save application
        Application::create([
            'unit_id'        => $unit->id,
            'surname'        => $data['surname'],
            'first_name'     => $data['first_name'],
            'middle_name'    => $data['middle_name'] ?? null,
            'email'          => $data['email'],
            'contact_number' => $data['contact_number'],
            'id_file'        => $filePath,
            'salary'         => $data['salary'] ?? null,
        ]);

        return back()->with('success', 'Application submitted successfully for unit ' . $unit->unit_number);
    }

    public function index()
    {
        $applications = Application::with('unit')->get();
        return view('admin.applications', compact('applications'));
    }
}
