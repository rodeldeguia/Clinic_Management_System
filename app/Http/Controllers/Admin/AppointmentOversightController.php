<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentOversightController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->latest()->paginate(20);
        return view('admin.appointments.index', compact('appointments'));
    }

    public function dailyView()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('time_slot')
            ->get();
            
        return view('admin.appointments.daily', compact('appointments'));
    }

    public function weeklyView()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
            ->orderBy('appointment_date')
            ->orderBy('time_slot')
            ->get()
            ->groupBy('appointment_date');
            
        return view('admin.appointments.weekly', compact('appointments', 'startOfWeek', 'endOfWeek'));
    }

    public function monthlyView()
    {
        $year = request('year', now()->year);
        $month = request('month', now()->month);
        
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereYear('appointment_date', $year)
            ->whereMonth('appointment_date', $month)
            ->get();
            
        return view('admin.appointments.monthly', compact('appointments', 'year', 'month'));
    }

    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'medicalRecord', 'billing', 'feedback'])->findOrFail($id);
        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        $appointment->save();

        return back()->with('success', 'Appointment status updated.');
    }

    public function reassignDoctor(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->doctor_id = $request->doctor_id;
        $appointment->save();

        return back()->with('success', 'Appointment reassigned successfully.');
    }

    public function statistics()
    {
        $total = Appointment::count();
        $completed = Appointment::where('status', 'Completed')->count();
        $cancelled = Appointment::where('status', 'Cancelled')->count();
        $noShow = Appointment::where('status', 'No-Show')->count();
        
        $busyTimes = Appointment::selectRaw('HOUR(time_slot) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->first();
            
        $appointmentsByDay = Appointment::selectRaw('DAYNAME(appointment_date) as day, COUNT(*) as count')
            ->groupBy('day')
            ->get();

        return view('admin.appointments.statistics', compact('total', 'completed', 'cancelled', 'noShow', 'busyTimes', 'appointmentsByDay'));
    }
}