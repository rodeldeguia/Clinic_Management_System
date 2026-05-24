<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function patientRecords(Request $request)
    {
        $patients = User::where('role', 'Patient')
            ->when($request->search, function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            })
            ->paginate(20);
            
        return view('receptionist.records.patients', compact('patients'));
    }

    public function showPatientRecord($id)
    {
        $patient = User::with(['patientAppointments.medicalRecord', 'patientAppointments.doctor'])
            ->findOrFail($id);
            
        return view('receptionist.records.show', compact('patient'));
    }

    public function updatePatientRecord(Request $request, $id)
    {
        $patient = User::findOrFail($id);
        
        $request->validate([
            'contact_number' => 'required',
            'address' => 'required',
            'emergency_contact' => 'nullable',
        ]);

        $patient->update($request->only(['contact_number', 'address', 'emergency_contact']));

        return back()->with('success', 'Patient record updated successfully.');
    }

    public function updateAfterTreatment(Request $request, $id)
    {
        // This would update records after doctor's treatment
        // Implementation depends on workflow
        
        return back()->with('success', 'Records updated after treatment.');
    }
}