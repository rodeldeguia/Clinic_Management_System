<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'Doctor');

        // Filters
        if ($request->specialization) {
            $query->where('specialization', $request->specialization);
        }
        if ($request->shift) {
            // Assuming shift is stored in shift_timing for doctors
            $query->where('shift_timing', $request->shift);
        }
        if ($request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        $doctors = $query->paginate(10);
        
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'contact_number' => 'required',
            'email_address' => 'required|email|unique:users,email_address',
            'specialization' => 'required',
            'qualification' => 'required',
            'shift_timing' => 'nullable',
        ]);

        $doctor = User::create([
            'username' => $request->username,
            'password_hashed' => Hash::make($request->password),
            'role' => 'Doctor',
            'is_active' => true,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'contact_number' => $request->contact_number,
            'email_address ' => $request->email_address,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'specialization' => $request->specialization,
            'license_number' => $request->qualification,
            'shift_timing' => $request->shift_timing,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor added successfully.');
    }

    public function show($id)
    {
        $doctor = User::with(['doctorAppointments', 'receivedFeedback'])->findOrFail($id);
        
        // Performance metrics
        $total_appointments = $doctor->doctorAppointments->count();
        $completed_appointments = $doctor->doctorAppointments->where('status', 'Completed')->count();
        $average_rating = $doctor->receivedFeedback->avg('rating');
        
        return view('admin.doctors.show', compact('doctor', 'total_appointments', 'completed_appointments', 'average_rating'));
    }

    public function edit($id)
    {
        $doctor = User::findOrFail($id);
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'contact_number' => 'required',
            'email_address' => 'required|email|unique:users,email_address,' . $id . ',user_id',
            'date_of_birth' => 'required|date',
            'specialization' => 'required',
            'qualification' => 'required',
            'shift_timing' => 'nullable',
        ]);

        $doctor->update($request->only([
            'firstname', 'lastname', 'contact_number', 'email_address',
            'address', 'date_of_birth', 'gender', 'specialization',
            'qualification', 'shift_timing'
        ]));

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy($id)
    {
        $doctor = User::findOrFail($id);
        
        // Check if doctor has appointments
        if ($doctor->doctorAppointments()->exists()) {
            return back()->with('error', 'Cannot delete doctor with existing appointments.');
        }
        
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }

    public function deactivate($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->is_active = false;
        $doctor->save();

        return back()->with('success', 'Doctor deactivated successfully.');
    }

    public function reactivate($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->is_active = true;
        $doctor->save();

        return back()->with('success', 'Doctor reactivated successfully.');
    }

    public function performance($id)
    {
        $doctor = User::findOrFail($id);
        
        $appointments_by_month = Appointment::where('doctor_id', $id)
            ->selectRaw('MONTH(appointment_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get();
            
        $feedback_ratings = Feedback::where('doctor_id', $id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->get();

        return view('admin.doctors.performance', compact('doctor', 'appointments_by_month', 'feedback_ratings'));
    }

    public function filter(Request $request)
    {
        return $this->index($request);
    }
}