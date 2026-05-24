<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleManagementController extends Controller
{
    public function index()
    {
        $schedules = DoctorSchedule::where('doctor_id', Auth::user()->user_id)
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();
            
        return view('doctor.schedule.index', compact('schedules'));
    }

    public function viewSchedule()
    {
        $schedules = DoctorSchedule::where('doctor_id', Auth::user()->user_id)
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();
            
        return view('doctor.schedule.view', compact('schedules'));
    }

    public function updateAvailability(Request $request)
    {
        $doctorId = Auth::user()->user_id;
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        foreach ($days as $day) {
            if (isset($request->schedule[$day])) {
                $scheduleData = $request->schedule[$day];
                
                // Check if this schedule already exists
                $schedule = DoctorSchedule::where('doctor_id', $doctorId)
                    ->where('day_of_week', $day)
                    ->first();
                
                if ($schedule) {
                    // Update existing schedule
                    $schedule->update([
                        'start_time' => $scheduleData['start_time'] ?? '09:00:00',
                        'end_time' => $scheduleData['end_time'] ?? '17:00:00',
                        'is_available' => isset($scheduleData['is_available']) ? true : false,
                        'slot_duration' => $scheduleData['slot_duration'] ?? 30,
                    ]);
                } else {
                    // Create new schedule
                    DoctorSchedule::create([
                        'doctor_id' => $doctorId,
                        'day_of_week' => $day,
                        'start_time' => $scheduleData['start_time'] ?? '09:00:00',
                        'end_time' => $scheduleData['end_time'] ?? '17:00:00',
                        'is_available' => isset($scheduleData['is_available']) ? true : false,
                        'slot_duration' => $scheduleData['slot_duration'] ?? 30,
                    ]);
                }
            }
        }
        
        return redirect()->route('doctor.schedule.index')
            ->with('success', 'Schedule updated successfully!');
    }

    public function availableSlots()
    {
        $schedules = DoctorSchedule::where('doctor_id', Auth::user()->user_id)
            ->where('is_available', true)
            ->get();
            
        return view('doctor.schedule.available-slots', compact('schedules'));
    }
}