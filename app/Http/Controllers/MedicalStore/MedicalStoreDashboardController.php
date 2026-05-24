<?php

namespace App\Http\Controllers\MedicalStore;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\MedicineStock;
use Illuminate\Support\Facades\Auth;

class MedicalStoreDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'pending_prescriptions' => Prescription::where('status', 'Prescribed')->count(),
            'dispensed_today' => Prescription::whereDate('dispensed_at', today())
                ->where('status', 'Dispensed')
                ->count(),
            'low_stock_items' => MedicineStock::where('quantity', '<', 10)->count(),
            'expiring_soon' => MedicineStock::whereDate('expiry_date', '<=', now()->addMonths(3))
                ->where('quantity', '>', 0)
                ->count(),
            'recent_prescriptions' => Prescription::with(['medicine', 'medicalRecord.appointment.patient'])
                ->latest('dispensed_at')
                ->take(10)
                ->get(),
        ];
        
        return view('medical-store.dashboard', $data);
    }
}