<?php

namespace App\Http\Controllers\MedicalStore;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillItem;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicalStoreBillingController extends Controller
{
    public function index()
    {
        // Get prescriptions that are dispensed but not yet billed
        $pendingPrescriptions = Prescription::with(['medicine', 'medicalRecord.appointment.patient', 'medicalRecord.doctor'])
            ->where('status', 'dispensed')
            ->whereDoesntHave('billingItems')  // Only prescriptions not yet billed
            ->orderBy('dispensed_at', 'desc')
            ->get();
        
        $bills = Billing::with(['patient', 'items'])
            ->whereHas('items', function($q) {
                $q->where('item_type', 'medicine');
            })
            ->orderBy('bill_date', 'desc')
            ->paginate(10);
        
        return view('medical-store.billing.index', compact('pendingPrescriptions', 'bills'));
    }

    public function store(Request $request)
{
    $prescriptionIds = explode(',', $request->prescription_ids);
    
    if (empty($prescriptionIds)) {
        return back()->with('error', 'No prescriptions selected.');
    }
    
    // Get the first prescription to get patient info
    $firstPrescription = Prescription::find($prescriptionIds[0]);
    $patientId = $firstPrescription->medicalRecord->appointment->patient_id;
    
    DB::beginTransaction();
    
    try {
        // Create bill
        $bill = Billing::create([
            'patient_id' => $patientId,
            'appointment_id' => null,
            'bill_date' => now(),
            'total_amount' => 0,
            'discount' => $request->discount ?? 0,
            'tax' => 0,
            'net_amount' => $request->net_amount,
            'payment_status' => 'pending',
            'generated_by' => Auth::user()->user_id,
        ]);
        
        $totalAmount = 0;
        
        // Add bill items for each prescription
        foreach ($prescriptionIds as $prescriptionId) {
            $prescription = Prescription::find($prescriptionId);
            if ($prescription) {
                $stockEntry = $prescription->medicine->stockEntries->first();
                $unitPrice = $stockEntry->unit_price ?? 50;
                $amount = $unitPrice * $prescription->quantity_dispensed;
                $totalAmount += $amount;
                
                BillItem::create([
                    'bill_id' => $bill->bill_id,
                    'item_type' => 'medicine',
                    'item_description' => $prescription->medicine->medicine_name,
                    'item_reference_id' => $prescriptionId,
                    'quantity' => $prescription->quantity_dispensed,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                ]);
            }
        }
        
        // Update bill totals
        $tax = ($totalAmount - ($request->discount ?? 0)) * 0.12;
        $bill->total_amount = $totalAmount;
        $bill->tax = $tax;
        $bill->save();
        
        DB::commit();
        
        return redirect()->route('medical-store.billing.show', $bill->bill_id)
            ->with('success', 'Bill generated successfully!');
            
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Error generating bill: ' . $e->getMessage());
    }
}

    public function show($id)
    {
        $bill = Billing::with(['patient', 'items', 'generatedBy'])
            ->findOrFail($id);
        
        return view('medical-store.billing.show', compact('bill'));
    }

    public function markAsPaid($id)
    {
        $bill = Billing::findOrFail($id);
        $bill->payment_status = 'paid';
        $bill->save();
        
        return back()->with('success', 'Bill marked as paid.');
    }
    
    public function patientHistory($patient_id)
    {
        $patient = User::findOrFail($patient_id);
        $bills = Billing::where('patient_id', $patient_id)
            ->whereHas('items', function($q) {
                $q->where('item_type', 'medicine');
            })
            ->with('items')
            ->orderBy('bill_date', 'desc')
            ->get();
        
        return view('medical-store.billing.patient-history', compact('patient', 'bills'));
    }
}