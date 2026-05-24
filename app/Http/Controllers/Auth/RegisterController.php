<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showPatientRegistrationForm()
    {
        return view('auth.register-patient');
    }

    public function registerPatient(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'contact_number' => 'required',
            'email_address' => 'nullable|email|unique:users,email_address',  // ← Changed from 'email' to 'email_address'
            'address' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'blood_group' => 'nullable',
            'emergency_contact' => 'nullable',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password_hashed' => Hash::make($request->password),
            'role' => 'patient',
            'is_active' => true,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'contact_number' => $request->contact_number,
            'email_address' => $request->email_address,  // ← Changed from 'email'
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => strtolower($request->gender),
            'blood_group' => $request->blood_group,
            'emergency_contact' => $request->emergency_contact,
            'registration_date' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}