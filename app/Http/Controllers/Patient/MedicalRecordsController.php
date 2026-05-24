<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class MedicalRecordsController extends Controller
{
    public function index()
    {
        // Use paginate() instead of get() to get a Paginator instance
        $appointments = Appointment::where('patient_id', Auth::user()->user_id)
            ->whereHas('medicalRecord')
            ->with(['doctor', 'medicalRecord'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);  // ← paginate() not get()
        
        return view('patient.medical-records.index', compact('appointments'));
    }

    public function show($id)
    {
        $appointment = Appointment::where('patient_id', Auth::user()->user_id)
            ->with(['doctor', 'medicalRecord.prescriptions.medicine'])
            ->findOrFail($id);
        
        return view('patient.medical-records.show', compact('appointment'));
    }

    public function prescriptions()
    {
        // Use paginate() instead of get()
        $prescriptions = Prescription::whereHas('medicalRecord.appointment', function($q) {
            $q->where('patient_id', Auth::user()->user_id);
        })->with(['medicine', 'medicalRecord.appointment.doctor'])
          ->orderBy('dispensed_at', 'desc')
          ->paginate(10);  // ← paginate() not get()
        
        return view('patient.medical-records.prescriptions', compact('prescriptions'));
    }

    public function prescriptionDetails($id)
    {
        $prescription = Prescription::whereHas('medicalRecord.appointment', function($q) {
            $q->where('patient_id', Auth::user()->user_id);
        })->with(['medicine', 'medicalRecord.appointment.doctor'])
          ->findOrFail($id);
        
        return view('patient.medical-records.prescription-details', compact('prescription'));
    }
}