<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientFeedbackController extends Controller
{
    public function create($appointment_id)
    {
        // Check if feedback already exists
        $existing = Feedback::where('appointment_id', $appointment_id)->first();
        if ($existing) {
            return redirect()->route('patient.feedback.my-feedback')
                ->with('error', 'You already submitted feedback for this appointment.');
        }
        
        $appointment = Appointment::where('patient_id', Auth::user()->user_id)
            ->where('status', 'Completed')
            ->findOrFail($appointment_id);
            
        return view('patient.feedback.create', compact('appointment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $appointment = Appointment::where('patient_id', Auth::user()->user_id)
            ->findOrFail($request->appointment_id);

        Feedback::create([
            'patient_id' => Auth::user()->user_id,
            'appointment_id' => $request->appointment_id,
            'doctor_id' => $appointment->doctor_id,
            'rating' => $request->rating,
            'comments' => $request->comments,
            'submitted_at' => now(),
            'is_public' => $request->is_public ?? false,
        ]);

        return redirect()->route('patient.feedback.my-feedback')
            ->with('success', 'Thank you for your feedback!');
    }

  public function myFeedback()
{
    $feedback = Feedback::where('patient_id', Auth::user()->user_id)
        ->with(['appointment.doctor'])
        ->orderBy('submitted_at', 'desc')
        ->paginate(10);  // ← paginate() not get()
    
    return view('patient.feedback.my-feedback', compact('feedback'));
}

    public function edit($id)
    {
        $feedback = Feedback::where('patient_id', Auth::user()->user_id)
            ->findOrFail($id);
            
        return view('patient.feedback.edit', compact('feedback'));
    }

    public function update(Request $request, $id)
    {
        $feedback = Feedback::where('patient_id', Auth::user()->user_id)
            ->findOrFail($id);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ]);

        $feedback->update([
            'rating' => $request->rating,
            'comments' => $request->comments,
        ]);

        return redirect()->route('patient.feedback.my-feedback')
            ->with('success', 'Feedback updated successfully.');
    }
}