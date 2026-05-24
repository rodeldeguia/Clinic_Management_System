<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillItem;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {
        $bills = Billing::with(['patient', 'appointment'])
            ->latest('bill_date')
            ->paginate(20);
            
        return view('receptionist.billing.index', compact('bills'));
    }

    public function generateForm($appointment_id)
    {
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($appointment_id);
        
        // Check if bill already exists
        $existingBill = Billing::where('appointment_id', $appointment_id)->first();
        if ($existingBill) {
            return redirect()->route('receptionist.billing.show', $existingBill->bill_id)
                ->with('info', 'Bill already exists for this appointment.');
        }
        
        return view('receptionist.billing.generate', compact('appointment'));
    }

    public function store(Request $request, $appointment_id)
    {
        $request->validate([
            'consultation_fee' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'insurance_claim_id' => 'nullable',
        ]);

        $appointment = Appointment::findOrFail($appointment_id);
        
        $total = $request->consultation_fee;
        $discount = $request->discount ?? 0;
        $tax = $request->tax ?? 0;
        $net = $total - $discount + $tax;

        $bill = Billing::create([
            'patient_id' => $appointment->patient_id,
            'appointment_id' => $appointment_id,
            'bill_date' => now(),
            'total_amount' => $total,
            'discount' => $discount,
            'tax' => $tax,
            'net_amount' => $net,
            'payment_status' => 'Pending',
            'generated_by' => Auth::user()->user_id,
            'insurance_claim_id' => $request->insurance_claim_id,
        ]);

        // Add bill item for consultation
        BillItem::create([
            'bill_id' => $bill->bill_id,
            'item_type' => 'Consultation',
            'item_description' => 'Doctor Consultation Fee',
            'item_reference_id' => $appointment_id,
            'quantity' => 1,
            'unit_price' => $request->consultation_fee,
            'amount' => $request->consultation_fee,
        ]);

        return redirect()->route('receptionist.billing.show', $bill->bill_id)
            ->with('success', 'Bill generated successfully.');
    }

    public function show($id)
    {
        $bill = Billing::with(['patient', 'appointment.doctor', 'items', 'generatedBy'])
            ->findOrFail($id);
            
        return view('receptionist.billing.show', compact('bill'));
    }

    public function update(Request $request, $id)
    {
        $bill = Billing::findOrFail($id);
        
        $request->validate([
            'payment_status' => 'required',
            'discount' => 'nullable|numeric',
        ]);

        $bill->payment_status = $request->payment_status;
        $bill->discount = $request->discount ?? $bill->discount;
        $bill->net_amount = $bill->total_amount - $bill->discount + $bill->tax;
        $bill->save();

        return back()->with('success', 'Bill updated successfully.');
    }

    public function markAsPaid($id)
    {
        $bill = Billing::findOrFail($id);
        $bill->payment_status = 'Paid';
        $bill->save();

        return back()->with('success', 'Bill marked as paid.');
    }
}