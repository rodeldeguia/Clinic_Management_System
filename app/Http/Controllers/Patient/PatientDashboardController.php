<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $patientId = Auth::user()->user_id;
        
        // Count upcoming appointments (scheduled or confirmed)
        $upcoming_appointments = Appointment::where('patient_id', $patientId)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->where('appointment_date', '>=', today())
            ->count();
        
        // Count completed appointments
        $completed_appointments = Appointment::where('patient_id', $patientId)
            ->where('status', 'completed')
            ->count();
        
        // Count pending bills
        $pending_bills = Billing::where('patient_id', $patientId)
            ->where('payment_status', 'pending')
            ->count();
        
        // Count feedback given
        $feedback_given = Feedback::where('patient_id', $patientId)->count();
        
        // Get upcoming appointments list
        $upcoming_appointments_list = Appointment::where('patient_id', $patientId)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->where('appointment_date', '>=', today())
            ->with('doctor')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('time_slot', 'asc')
            ->get();
        
        return view('patient.dashboard', compact(
            'upcoming_appointments',
            'completed_appointments',
            'pending_bills',
            'feedback_given',
            'upcoming_appointments_list'
        ));
    }
}