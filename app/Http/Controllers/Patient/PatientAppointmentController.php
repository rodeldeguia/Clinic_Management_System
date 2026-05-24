<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\log;


class PatientAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('patient_id', Auth::user()->user_id)
            ->with('doctor')
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
            
        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,user_id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required',
            'reason_for_visit' => 'nullable',
        ]);

        // Check if slot is available
        $existing = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('time_slot', $request->time_slot)
            ->whereNotIn('status', ['Cancelled', 'No-Show'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'This time slot is already booked.');
        }

        $appointment = Appointment::create([
            'patient_id' => Auth::user()->user_id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'time_slot' => $request->time_slot,
            'status' => 'Scheduled',
            'reason_for_visit' => $request->reason_for_visit,
            'created_by' => Auth::user()->user_id,
            'created_at' => now(),
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment booked successfully. Please wait for confirmation.');
    }

    public function show($id)
    {
        $appointment = Appointment::where('patient_id', Auth::user()->user_id)
            ->with(['doctor', 'medicalRecord', 'billing', 'feedback'])
            ->findOrFail($id);
            
        return view('patient.appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        $appointment = Appointment::where('patient_id', Auth::user()->user_id)
            ->whereIn('status', ['Scheduled', 'Confirmed'])
            ->findOrFail($id);
            
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        
        return view('patient.appointments.edit', compact('appointment', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::where('patient_id', Auth::user()->user_id)
            ->findOrFail($id);

        $request->validate([
            'doctor_id' => 'required|exists:users,user_id',
            'appointment_date' => 'required|date',
            'time_slot' => 'required',
        ]);

        // Check availability for new slot
        $existing = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('time_slot', $request->time_slot)
            ->where('appointment_id', '!=', $id)
            ->whereNotIn('status', ['Cancelled', 'No-Show'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'The new time slot is not available.');
        }

        $appointment->update([
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'time_slot' => $request->time_slot,
            'status' => 'Scheduled', // Reset status when rescheduled
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment rescheduled successfully.');
    }

    public function destroy($id)
    {
        $appointment = Appointment::where('patient_id', Auth::user()->user_id)
            ->whereIn('status', ['Scheduled', 'Confirmed'])
            ->findOrFail($id);
            
        $appointment->status = 'Cancelled';
        $appointment->cancellation_reason = 'Cancelled by patient';
        $appointment->save();

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    public function availableDoctors()
    {
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        return view('patient.appointments.available-doctors', compact('doctors'));
    }

    public function availableSlots($doctor_id, Request $request)
{
    $date = $request->date;
    
    // Debug log
    Log::info('availableSlots called', [
        'doctor_id' => $doctor_id,
        'date' => $date
    ]);
    
    if (!$doctor_id || !$date) {
        return response()->json(['slots' => [], 'error' => 'Missing parameters']);
    }
    
    $dayOfWeek = date('l', strtotime($date));
    
    Log::info('Day of week: ' . $dayOfWeek);
    
    $schedule = \App\Models\DoctorSchedule::where('doctor_id', $doctor_id)
        ->where('day_of_week', $dayOfWeek)
        ->where('is_available', 1)
        ->first();
    
    Log::info('Schedule found: ' . ($schedule ? 'Yes' : 'No'));
    
    if (!$schedule) {
        return response()->json(['slots' => [], 'message' => 'Doctor not available on ' . $dayOfWeek]);
    }
    
    $bookedSlots = \App\Models\Appointment::where('doctor_id', $doctor_id)
        ->whereDate('appointment_date', $date)
        ->whereNotIn('status', ['cancelled', 'no-show'])
        ->pluck('time_slot')
        ->toArray();
    
    $slots = [];
    $start = strtotime($schedule->start_time);
    $end = strtotime($schedule->end_time);
    $duration = $schedule->slot_duration * 60;
    
    for ($time = $start; $time < $end; $time += $duration) {
        $slotTime = date('H:i:s', $time);
        $slotDisplay = date('h:i A', $time);
        
        if (!in_array($slotTime, $bookedSlots)) {
            $slots[] = [
                'value' => $slotTime,
                'display' => $slotDisplay
            ];
        }
    }
    
    return response()->json(['slots' => $slots]);
}
}