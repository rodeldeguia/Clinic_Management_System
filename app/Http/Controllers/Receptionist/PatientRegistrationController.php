<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PatientRegistrationController extends Controller
{
    public function create()
    {
        return view('receptionist.patients.register');
    }

    public function store(Request $request)
    {
        // Log incoming data
        Log::info('Patient registration started');
        Log::info($request->all());

        // Validate
        $validator = validator($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'contact_number' => 'required',
            'email_address' => 'nullable|email|unique:users,email_address',
            'address' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'blood_group' => 'nullable',
            'emergency_contact' => 'nullable',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ' . $validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        Log::info('Validation passed');

        // Generate unique username
        $baseUsername = strtolower($request->firstname . '.' . $request->lastname);
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        // Default password
        $defaultPassword = 'password';

        try {
            Log::info('Attempting to insert patient');
            
            $patientId = DB::table('users')->insertGetId([
                'username' => $username,
                'password_hashed' => Hash::make($defaultPassword),
                'role' => 'patient',
                'is_active' => 1,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'contact_number' => $request->contact_number,
                'email_address' => $request->email_address,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'emergency_contact' => $request->emergency_contact,
                'registration_date' => now(),
                'created_at' => now(),
            ]);

            Log::info('Patient inserted with ID: ' . $patientId);

            if ($patientId) {
                return redirect()->route('receptionist.patients.show', $patientId)
                    ->with('success', 'Patient registered successfully! Username: ' . $username . ', Password: ' . $defaultPassword);
            } else {
                return back()->with('error', 'Failed to save patient.')->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Exception caught: ' . $e->getMessage());
            return back()->with('error', 'Database error: ' . $e->getMessage())->withInput();
        }
    }

    public function verifyForm()
    {
        return view('receptionist.patients.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
        ]);

        $patient = User::where('role', 'patient')
            ->where(function($q) use ($request) {
                $q->where('username', $request->identifier)
                  ->orWhere('contact_number', $request->identifier)
                  ->orWhere('email_address', $request->identifier)
                  ->orWhere('user_id', $request->identifier);
            })
            ->first();

        if (!$patient) {
            return back()->with('error', 'Patient not found.');
        }

        return redirect()->route('receptionist.patients.show', $patient->user_id);
    }

    public function show($id)
    {
        $patient = User::with(['patientAppointments', 'generatedBills'])->findOrFail($id);
        return view('receptionist.patients.show', compact('patient'));
    }
}