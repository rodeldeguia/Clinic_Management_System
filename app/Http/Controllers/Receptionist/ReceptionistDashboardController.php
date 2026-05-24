<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReceptionistDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::where('status', 'scheduled')->count(),
            'new_patients_today' => User::where('role', 'patient')
                ->whereDate('registration_date', today())
                ->count(),
            'today_revenue' => Billing::whereDate('bill_date', today())
                ->where('payment_status', 'paid')
                ->sum('net_amount'),
            'today_appointments_list' => Appointment::with(['patient', 'doctor'])
                ->whereDate('appointment_date', today())
                ->orderBy('time_slot')
                ->get(),
        ];
        
        return view('receptionist.dashboard', $data);
    }
}