<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Tenant;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'unit' => 'required',
            'price' => 'required|numeric',
            'surname' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'email' => 'required|email',
            'contact' => 'required|string',
            'id_upload' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'salary' => 'nullable|numeric',
            'lease_start' => 'nullable|date',
            'lease_duration' => 'nullable|integer',
        ]);

        // Map form fields → DB fields
        $data = [
            'title'          => $validated['title'],
            'unit_id'        => $validated['unit'],   // maps form "unit" → DB "unit_id"
            'price'          => $validated['price'],
            'surname'        => $validated['surname'],
            'first_name'     => $validated['first_name'],
            'middle_name'    => $validated['middle_name'] ?? null,
            'email'          => $validated['email'],
            'contact_number' => $validated['contact'], // maps "contact" → "contact_number"
            'salary'         => $validated['salary'] ?? null,
            'lease_start'    => $validated['lease_start'] ?? null,
            'lease_duration' => $validated['lease_duration'] ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('id_upload')) {
            $filename = time().'_'.$request->file('id_upload')->getClientOriginalName();
            $request->file('id_upload')->move(public_path('uploads/applications'), $filename);
            $data['id_file'] = $filename; // maps "id_upload" → "id_file"
        }

        if ($request->ajax()) {
        return response()->json(['success' => true]);
    }

    return back()->with('success', '✅ Application submitted successfully!');
    }


    public function index()
    {
        $applications = Application::with('unit')->get();
        return view('admin.applications', compact('applications'));
    }

    // ✅ Accept Application
        public function accept($id)
    {
        $app = Application::findOrFail($id);
        $app->update(['status' => 'accepted']);

        // Only create tenant if email is not already used
        $existingTenant = Tenant::where('email', $app->email)->first();
        if (!$existingTenant) {
            Tenant::create([
                'title'       => $app->title,
                'last_name'   => $app->surname,
                'first_name'  => $app->first_name,
                'middle_name' => $app->middle_name,
                'email'       => $app->email,
                'contact'     => $app->contact_number,
                'unit_id'     => $app->unit_id,
                'salary'      => $app->salary,
                'id_upload'   => $app->id_file,
            ]);
        }

        // Redirect to addTenant with prefillTenant session
        return redirect()->route('admin.tenants')->with('prefillTenant', $app->toArray());
    }

    // ✅ Decline Application
    public function decline($id)
    {
        $app = Application::findOrFail($id);

        $app->update(['status' => 'declined']);

        return redirect()->back()->with('success', 'Application declined.');
    }
}
