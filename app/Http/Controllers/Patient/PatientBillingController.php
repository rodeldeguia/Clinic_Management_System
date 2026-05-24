<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class PatientBillingController extends Controller
{
   public function index()
{
    $bills = Billing::where('patient_id', Auth::user()->user_id)
        ->with(['appointment.doctor'])
        ->orderBy('bill_date', 'desc')
        ->paginate(10);  // ← paginate() not get()
    
    return view('patient.billing.index', compact('bills'));
}

    public function show($id)
    {
        $bill = Billing::where('patient_id', Auth::user()->user_id)
            ->with(['appointment.doctor', 'items'])
            ->findOrFail($id);
            
        return view('patient.billing.show', compact('bill'));
    }

    public function downloadInvoice($id)
    {
        $bill = Billing::where('patient_id', Auth::user()->user_id)
            ->with(['appointment.doctor', 'items'])
            ->findOrFail($id);
            
        // Generate PDF invoice
        // You'll need to install barryvdh/laravel-dompdf or similar
        
        return back()->with('success', 'Invoice downloaded.');
    }
}