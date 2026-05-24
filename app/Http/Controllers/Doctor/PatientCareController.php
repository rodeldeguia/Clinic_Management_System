<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientCareController extends Controller
{
    public function treatPatient($appointment_id)
{
    $appointment = Appointment::where('doctor_id', Auth::user()->user_id)
        ->with('patient')
        ->findOrFail($appointment_id);
    
    $medicalRecord = MedicalRecord::where('appointment_id', $appointment_id)->first();
    $medicines = Medicine::all();  // Load medicines for the dropdown
    
    return view('doctor.patient-care.treat', compact('appointment', 'medicalRecord', 'medicines'));
}

    // This saves to medical_records table
    public function updateTreatmentRecord(Request $request, $appointment_id)
{
    $request->validate([
        'diagnosis' => 'required',
        'notes' => 'nullable',
    ]);

    // Save to medical_records table
    $medicalRecord = MedicalRecord::updateOrCreate(
        ['appointment_id' => $appointment_id],
        [
            'doctor_id' => Auth::user()->user_id,
            'diagnosis' => $request->diagnosis,
            'prescription_text' => $request->prescription_text ?? '',
            'notes' => $request->notes,
            'created_at' => now(),
        ]
    );

    // Save prescriptions if any
    if ($request->has('prescriptions') && is_array($request->prescriptions)) {
        foreach ($request->prescriptions as $prescription) {
            if (!empty($prescription['medicine_id']) && !empty($prescription['dosage']) && !empty($prescription['quantity'])) {
                Prescription::create([
                    'record_id' => $medicalRecord->record_id,
                    'medicine_id' => $prescription['medicine_id'],
                    'dosage' => $prescription['dosage'],
                    'quantity_prescribed' => $prescription['quantity'],
                    'quantity_dispensed' => 0,
                    'status' => 'prescribed',
                ]);
            }
        }
    }

    // Update appointment status to completed
    $appointment = Appointment::find($appointment_id);
    $appointment->status = 'completed';
    $appointment->save();

    return redirect()->route('doctor.dashboard')
        ->with('success', 'Diagnosis and prescriptions saved successfully!');
}
    // Show prescription form
    public function prescribeForm($appointment_id)
    {
        $appointment = Appointment::where('doctor_id', Auth::user()->user_id)
            ->with('patient')
            ->findOrFail($appointment_id);
        
        $medicalRecord = MedicalRecord::where('appointment_id', $appointment_id)->first();
        if (!$medicalRecord) {
            return redirect()->route('doctor.patient-care.treat', $appointment_id)
                ->with('error', 'Please save diagnosis first.');
        }
        
        $medicines = Medicine::all();
        
        return view('doctor.patient-care.prescribe', compact('appointment', 'medicines'));
    }

    // This saves to prescriptions table
    public function prescribeMedicine(Request $request, $appointment_id)
    {
        $request->validate([
            'prescriptions' => 'required|array|min:1',
            'prescriptions.*.medicine_id' => 'required|exists:medicines,medicine_id',
            'prescriptions.*.dosage' => 'required|string',
            'prescriptions.*.quantity' => 'required|integer|min:1',
        ]);

        // Get the medical record for this appointment
        $medicalRecord = MedicalRecord::where('appointment_id', $appointment_id)->first();
        
        if (!$medicalRecord) {
            return redirect()->route('doctor.patient-care.treat', $appointment_id)
                ->with('error', 'Please save diagnosis first.');
        }

        // Save each prescription to prescriptions table
        foreach ($request->prescriptions as $prescription) {
            Prescription::create([
                'record_id' => $medicalRecord->record_id,
                'medicine_id' => $prescription['medicine_id'],
                'dosage' => $prescription['dosage'],
                'quantity_prescribed' => $prescription['quantity'],
                'quantity_dispensed' => 0,
                'status' => 'prescribed',
            ]);
        }

        // Update appointment status to completed
        $appointment = Appointment::find($appointment_id);
        $appointment->status = 'completed';
        $appointment->save();

        return redirect()->route('doctor.dashboard')
            ->with('success', 'Prescriptions saved successfully! Consultation completed.');
    }

    public function myPrescriptions()
    {
        $doctorId = Auth::user()->user_id;
        
        $prescriptions = Prescription::whereHas('medicalRecord', function($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })
        ->with(['medicine', 'medicalRecord.appointment.patient', 'dispensedBy'])
        ->orderBy('prescription_id', 'desc')
        ->paginate(15);
        
        return view('doctor.patient-care.my-prescriptions', compact('prescriptions'));
    }
}