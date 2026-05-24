<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Billing;
use Illuminate\Http\Request;

class PatientOversightController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'Patient');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_number', 'like', '%' . $request->search . '%');
            });
        }

        $patients = $query->paginate(15);
        return view('admin.patients.index', compact('patients'));
    }

    public function show($id)
    {
        $patient = User::with(['patientAppointments', 'givenFeedback', 'generatedBills'])->findOrFail($id);
        
        $appointments = $patient->patientAppointments()->with('doctor')->latest()->get();
        $medical_records = [];
        
        foreach ($patient->patientAppointments as $appointment) {
            if ($appointment->medicalRecord) {
                $medical_records[] = $appointment->medicalRecord;
            }
        }
        
        $bills = $patient->generatedBills;
        
        return view('admin.patients.show', compact('patient', 'appointments', 'medical_records', 'bills'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    public function merge($id1, $id2)
    {
        $patient1 = User::findOrFail($id1);
        $patient2 = User::findOrFail($id2);

        // Move all appointments from patient2 to patient1
        Appointment::where('patient_id', $id2)->update(['patient_id' => $id1]);
        
        // Move all bills from patient2 to patient1
        Billing::where('patient_id', $id2)->update(['patient_id' => $id1]);
        
        // Delete patient2
        $patient2->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Patients merged successfully.');
    }

    public function flagSpecialCondition(Request $request, $id)
    {
        $patient = User::findOrFail($id);
        
        // Store special condition in notes or a dedicated field
        // You might want to add a 'special_notes' column to users table
        $patient->address = $patient->address . "\n[FLAG: " . $request->condition . "]";
        $patient->save();

        return back()->with('success', 'Patient flagged successfully.');
    }

    public function medicalHistory($id)
    {
        $patient = User::findOrFail($id);
        $medical_records = [];
        
        foreach ($patient->patientAppointments as $appointment) {
            if ($appointment->medicalRecord) {
                $medical_records[] = $appointment->medicalRecord;
            }
        }
        
        return view('admin.patients.medical-history', compact('patient', 'medical_records'));
    }

    public function billingHistory($id)
    {
        $patient = User::findOrFail($id);
        $bills = Billing::where('patient_id', $id)->with('items')->get();
        
        return view('admin.patients.billing-history', compact('patient', 'bills'));
    }
}