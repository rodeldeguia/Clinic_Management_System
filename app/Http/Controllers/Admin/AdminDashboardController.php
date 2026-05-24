<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Billing;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_doctors' => User::where('role', 'Doctor')->count(),
            'total_receptionists' => User::where('role', 'Receptionist')->count(),
            'total_patients' => User::where('role', 'Patient')->count(),
            'total_appointments_today' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::where('status', 'Scheduled')->count(),
            'completed_appointments' => Appointment::where('status', 'Completed')->count(),
            'total_revenue' => Billing::where('payment_status', 'Paid')->sum('net_amount'),
            'pending_bills' => Billing::where('payment_status', 'Pending')->count(),
            'recent_appointments' => Appointment::with(['patient', 'doctor'])->latest()->take(5)->get(),
            'recent_patients' => User::where('role', 'Patient')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}