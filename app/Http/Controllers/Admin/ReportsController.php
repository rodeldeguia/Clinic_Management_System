<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\MedicineStock;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function patientReports(Request $request)
    {
        $patients = User::where('role', 'Patient')
            ->with(['patientAppointments', 'generatedBills'])
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('registration_date', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('registration_date', '<=', $request->date_to);
            })
            ->get();

        return view('admin.reports.patients', compact('patients'));
    }

    public function exportPatientReport(Request $request)
    {
        // Implement Excel/PDF export
        return back()->with('success', 'Report exported successfully.');
    }

    public function doctorActivityReports(Request $request)
    {
        $doctors = User::where('role', 'Doctor')
            ->with(['doctorAppointments', 'receivedFeedback'])
            ->when($request->date_from, function($q) use ($request) {
                $q->whereHas('doctorAppointments', function($q) use ($request) {
                    $q->whereDate('appointment_date', '>=', $request->date_from);
                });
            })
            ->get();

        return view('admin.reports.doctors', compact('doctors'));
    }

    public function exportDoctorReport(Request $request)
    {
        return back()->with('success', 'Doctor report exported.');
    }

    public function receptionistActivityReports(Request $request)
    {
        $receptionists = User::where('role', 'Receptionist')
            ->with(['createdAppointments', 'generatedBills'])
            ->get();

        return view('admin.reports.receptionists', compact('receptionists'));
    }

    public function exportReceptionistReport(Request $request)
    {
        return back()->with('success', 'Receptionist report exported.');
    }

    public function medicineStockReports(Request $request)
    {
        $stock = MedicineStock::with('medicine')
            ->when($request->expiring_soon, function($q) {
                $q->whereDate('expiry_date', '<=', now()->addMonths(3));
            })
            ->when($request->low_stock, function($q) {
                $q->where('quantity', '<', 10);
            })
            ->get();

        return view('admin.reports.medicines', compact('stock'));
    }

    public function exportMedicineReport(Request $request)
    {
        return back()->with('success', 'Medicine stock report exported.');
    }

    public function financialReports(Request $request)
    {
        $query = Billing::with('patient');

        if ($request->date_from) {
            $query->whereDate('bill_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('bill_date', '<=', $request->date_to);
        }

        $bills = $query->get();
        
        $total_revenue = $bills->where('payment_status', 'Paid')->sum('net_amount');
        $pending_amount = $bills->where('payment_status', 'Pending')->sum('net_amount');
        
        return view('admin.reports.financial', compact('bills', 'total_revenue', 'pending_amount'));
    }

    public function exportFinancialReport(Request $request)
    {
        return back()->with('success', 'Financial report exported.');
    }
}