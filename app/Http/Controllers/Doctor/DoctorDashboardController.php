<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $doctorId = Auth::user()->user_id;
        
        // Count today's appointments
        $today_appointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->count();
        
        // Count upcoming appointments (confirmed and future)
        $upcoming_appointments = Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['confirmed'])
            ->where('appointment_date', '>', today())
            ->count();
        
        // Count pending appointments (scheduled waiting for confirmation)
        $pending_appointments = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'scheduled')
            ->count();
        
        // Count total patients (unique patients)
        $total_patients = Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count('patient_id');
        
        // Get average rating
        $average_rating = Feedback::where('doctor_id', $doctorId)->avg('rating') ?? 0;
        
        // Get today's appointments list
        $today_appointments_list = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->with('patient')
            ->orderBy('time_slot', 'asc')
            ->get();
        
        // Get pending appointments list (waiting for confirmation)
        $pending_appointments_list = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', today())
            ->with('patient')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('time_slot', 'asc')
            ->get();
        
        // Get upcoming confirmed appointments
        $upcoming_appointments_list = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'confirmed')
            ->where('appointment_date', '>', today())
            ->with('patient')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('time_slot', 'asc')
            ->get();
        
        return view('doctor.dashboard', compact(
            'today_appointments',
            'upcoming_appointments',
            'pending_appointments',
            'total_patients',
            'average_rating',
            'today_appointments_list',
            'pending_appointments_list',
            'upcoming_appointments_list'
        ));
    }
}