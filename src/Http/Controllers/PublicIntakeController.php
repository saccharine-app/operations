<?php

namespace Saccharine\Operations\Http\Controllers;

use Saccharine\Operations\Models\IntakeQueue;
use Illuminate\Http\Request;

class PublicIntakeController extends Controller
{
    // Shows the HTML form
    public function create()
    {
        return view('intake.form');
    }

    // Handles the form submission
    public function store(Request $request)
    {
        // Validate the incoming data so people can't submit junk
        $validated = $request->validate([
            'submitted_name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'message' => 'nullable|string', // We will stuff this into the raw_payload
        ]);

        // Create the record in the database
        IntakeQueue::create([
            'status' => 'pending',
            'source_channel' => 'Public Website Form',
            'submitted_name' => $validated['submitted_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'raw_payload' => ['message' => $validated['message'] ?? ''],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Thank you. We have received your information.');
    }
}
