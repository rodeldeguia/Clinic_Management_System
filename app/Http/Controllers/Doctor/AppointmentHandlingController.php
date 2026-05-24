<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AppointmentHandlingController extends Controller
{
  public function index(Request $request)
{
    $query = Appointment::where('doctor_id', Auth::user()->user_id)
        ->with('patient');
    
    switch($request->filter) {
        case 'pending':
            $query->where('status', 'scheduled');
            break;
        case 'confirmed':
            $query->where('status', 'confirmed');
            break;
        case 'today':
            $query->whereDate('appointment_date', today());
            break;
        case 'upcoming':
            $query->where('status', 'confirmed')
                  ->where('appointment_date', '>', today());
            break;
        case 'completed':
            $query->where('status', 'completed');
            break;
        case 'all':
            // No filter
            break;
        default:
            // Default show pending and confirmed
            $query->whereIn('status', ['scheduled', 'confirmed']);
    }
    
    $appointments = $query->orderBy('appointment_date', 'asc')
        ->orderBy('time_slot', 'asc')
        ->paginate(15);
    
    return view('doctor.appointments.index', compact('appointments'));
}

    public function show($id)
    {
        $appointment = Appointment::where('doctor_id', Auth::user()->user_id)
            ->with(['patient', 'medicalRecord', 'billing'])
            ->findOrFail($id);
            
        return view('doctor.appointments.show', compact('appointment'));
    }

    public function startConsultation($id)
    {
        $appointment = Appointment::where('doctor_id', Auth::user()->user_id)
            ->findOrFail($id);
            
        $appointment->status = 'Confirmed';
        $appointment->save();
        
        return redirect()->route('doctor.patient-care.treat', $id);
    }

    public function completeConsultation($id)
    {
        $appointment = Appointment::where('doctor_id', Auth::user()->user_id)
            ->findOrFail($id);
            
        $appointment->status = 'Completed';
        $appointment->save();
        
        return redirect()->route('doctor.dashboard')->with('success', 'Consultation completed.');
    }
        public function confirm($id)
{
    $appointment = Appointment::where('doctor_id', Auth::user()->user_id)
        ->findOrFail($id);
    
    $appointment->status = 'confirmed';  // Make sure this exists in ENUM
    $appointment->save();
    
    return redirect()->route('doctor.dashboard')->with('success', 'Appointment confirmed successfully.');
}

public function cancel($id)
{
    $appointment = Appointment::where('doctor_id', Auth::user()->user_id)
        ->findOrFail($id);
    
    $appointment->status = 'cancelled';  // Make sure this exists in ENUM
    $appointment->cancellation_reason = 'Cancelled by doctor';
    $appointment->save();
    
    return redirect()->route('doctor.dashboard')->with('success', 'Appointment cancelled.');
}
}