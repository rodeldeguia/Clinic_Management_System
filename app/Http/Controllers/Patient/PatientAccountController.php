<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PatientAccountController extends Controller
{
    public function profile()
    {
        $patient = Auth::user();
        return view('patient.account.profile', compact('patient'));
    }

    public function edit()
    {
        $patient = Auth::user();
        return view('patient.account.edit', compact('patient'));
    }

    public function update(Request $request)
    {
        $patient = Auth::user();

        $request->validate([
            'contact_number' => 'required',
            'email_address' => 'required|email|unique:users,email_address,' . $patient->user_id . ',user_id',  // ← Changed from 'email'
            'address' => 'required',
            'emergency_contact' => 'nullable',
        ]);

        User::where('user_id', $patient->user_id)->update([
            'contact_number' => $request->contact_number,
            'email_address' => $request->email_address,  // ← Changed from 'email'
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
        ]);

        return redirect()->route('patient.account.profile')->with('success', 'Profile updated successfully.');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $patient = Auth::user();
        
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile_photos', 'public');
            
            User::where('user_id', $patient->user_id)->update([
                'profile_photo' => $path
            ]);
        }

        return back()->with('success', 'Profile photo updated successfully.');
    }
}