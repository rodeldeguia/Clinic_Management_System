<?php

namespace App\Http\Controllers\MedicalStore;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\MedicineStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionHandlingController extends Controller
{
  public function index(Request $request)
{
    $query = Prescription::with(['medicine', 'medicalRecord.appointment.patient', 'medicalRecord.doctor']);
    
    if ($request->status == 'dispensed') {
        $query->where('status', 'dispensed');
    } elseif ($request->status == 'partial') {
        $query->where('status', 'prescribed')
              ->whereRaw('quantity_dispensed > 0 AND quantity_dispensed < quantity_prescribed');
    } else {
        // Pending - not fully dispensed
        $query->where('status', '!=', 'dispensed');
    }
    
    $prescriptions = $query->orderBy('prescription_id', 'desc')->paginate(20);
    
    $pending_count = Prescription::where('status', '!=', 'dispensed')->count();
    
    return view('medical-store.prescriptions.index', compact('prescriptions', 'pending_count'));
}
    public function show($id)
    {
        $prescription = Prescription::with(['medicine', 'medicalRecord.appointment.patient', 'medicalRecord.appointment.doctor'])
            ->findOrFail($id);
            
        // Get available stock for this medicine
        $stock = MedicineStock::where('medicine_id', $prescription->medicine_id)
            ->where('quantity', '>', 0)
            ->whereDate('expiry_date', '>', now())
            ->get();
            
        return view('medical-store.prescriptions.show', compact('prescription', 'stock'));
    }

    public function dispense(Request $request, $id)
{
    $prescription = Prescription::findOrFail($id);
    
    $request->validate([
        'quantity_dispensed' => 'required|integer|min:1',
    ]);

    $remaining = $prescription->quantity_prescribed - $prescription->quantity_dispensed;
    
    if ($request->quantity_dispensed > $remaining) {
        return back()->with('error', 'Cannot dispense more than remaining quantity. Remaining: ' . $remaining);
    }

    // Update quantity dispensed
    $newDispensed = $prescription->quantity_dispensed + $request->quantity_dispensed;
    $prescription->quantity_dispensed = $newDispensed;
    
    // Update status
    if ($newDispensed >= $prescription->quantity_prescribed) {
        $prescription->status = 'dispensed';
    } else {
        $prescription->status = 'partially_dispensed';
    }
    
    // Set dispensed by and timestamp
    $prescription->dispensed_by = Auth::user()->user_id;
    $prescription->dispensed_at = now();
    
    $prescription->save();

    // Also update stock quantity
    $stock = MedicineStock::where('medicine_id', $prescription->medicine_id)
        ->where('quantity', '>', 0)
        ->orderBy('expiry_date', 'asc')
        ->first();
    
    if ($stock) {
        $stock->quantity -= $request->quantity_dispensed;
        $stock->last_updated = now();
        $stock->save();
    }

    return redirect()->route('medical-store.prescriptions.index')
        ->with('success', 'Prescription dispensed successfully!');
}

    public function pending()
    {
        $prescriptions = Prescription::where('status', 'Prescribed')
            ->with(['medicine', 'medicalRecord.appointment.patient'])
            ->paginate(20);
            
        return view('medical-store.prescriptions.pending', compact('prescriptions'));
    }

    public function dispensed()
    {
        $prescriptions = Prescription::where('status', 'Dispensed')
            ->with(['medicine', 'medicalRecord.appointment.patient'])
            ->latest('dispensed_at')
            ->paginate(20);
            
        return view('medical-store.prescriptions.dispensed', compact('prescriptions'));
    }
}