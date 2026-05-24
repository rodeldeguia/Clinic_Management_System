<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AppointmentSchedulingController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->paginate(20);
            
        return view('receptionist.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = User::where('role', 'patient')->where('is_active', 1)->get();
        $doctors = User::where('role', 'doctor')->where('is_active', 1)->get();
        
        return view('receptionist.appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        // Log incoming data
        Log::info('Appointment store started');
        Log::info($request->all());

        // Validate
        $validator = validator($request->all(), [
            'patient_id' => 'required|exists:users,user_id',
            'doctor_id' => 'required|exists:users,user_id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required',
            'reason_for_visit' => 'nullable',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ' . $validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        Log::info('Validation passed');

        // Check if slot is available
        $existing = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('time_slot', $request->time_slot)
            ->whereNotIn('status', ['cancelled', 'no-show'])
            ->exists();

        if ($existing) {
            Log::error('Time slot already booked');
            return back()->with('error', 'This time slot is already booked. Please select another time.')->withInput();
        }

        try {
            Log::info('Attempting to insert appointment');
            
            $appointmentId = DB::table('appointments')->insertGetId([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_date' => $request->appointment_date,
                'time_slot' => $request->time_slot,
                'status' => 'scheduled',
                'reason_for_visit' => $request->reason_for_visit,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
            ]);

            Log::info('Appointment inserted with ID: ' . $appointmentId);

            if ($appointmentId) {
                return redirect()->route('receptionist.appointments.index')
                    ->with('success', 'Appointment scheduled successfully for ' . date('F j, Y', strtotime($request->appointment_date)) . ' at ' . date('g:i A', strtotime($request->time_slot)));
            } else {
                return back()->with('error', 'Failed to save appointment.')->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Exception caught: ' . $e->getMessage());
            return back()->with('error', 'Database error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        
        return view('receptionist.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validator = validator($request->all(), [
            'patient_id' => 'required|exists:users,user_id',
            'doctor_id' => 'required|exists:users,user_id',
            'appointment_date' => 'required|date',
            'time_slot' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $appointment->update([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_date' => $request->appointment_date,
                'time_slot' => $request->time_slot,
                'status' => $request->status,
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            return redirect()->route('receptionist.appointments.index')
                ->with('success', 'Appointment updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Database error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->status = 'cancelled';
            $appointment->cancellation_reason = 'Cancelled by receptionist';
            $appointment->save();

            return redirect()->route('receptionist.appointments.index')
                ->with('success', 'Appointment cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function availableSlots(Request $request)
    {
        $doctor_id = $request->doctor_id;
        $date = $request->date;
        
        if (!$doctor_id || !$date) {
            return response()->json(['slots' => []]);
        }
        
        // Get the day of week for the selected date
        $dayOfWeek = date('l', strtotime($date));
        
        // Get doctor's schedule for that day
        $schedule = DoctorSchedule::where('doctor_id', $doctor_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', 1)
            ->first();
        
        // If doctor is not available on this day, return no slots
        if (!$schedule) {
            return response()->json([
                'slots' => [], 
                'message' => 'Doctor not available on ' . $dayOfWeek
            ]);
        }
        
        // Get already booked slots for this doctor on this date
        $bookedSlots = Appointment::where('doctor_id', $doctor_id)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no-show'])
            ->pluck('time_slot')
            ->map(function($slot) {
                return date('h:i A', strtotime($slot));
            })
            ->toArray();
        
        // Generate available time slots based on doctor's schedule
        $slots = [];
        $start = strtotime($schedule->start_time);
        $end = strtotime($schedule->end_time);
        $duration = $schedule->slot_duration * 60; // Convert minutes to seconds
        
        for ($time = $start; $time < $end; $time += $duration) {
            $slotTime = date('H:i:s', $time);
            $slotDisplay = date('h:i A', $time);
            
            // Only show slots that are not already booked
            if (!in_array($slotDisplay, $bookedSlots)) {
                $slots[] = [
                    'value' => $slotTime,
                    'display' => $slotDisplay
                ];
            }
        }
        
        return response()->json([
            'slots' => $slots,
            'doctor_name' => User::find($doctor_id)->firstname ?? 'Doctor',
            'date' => $date,
            'day' => $dayOfWeek
        ]);
    }
}