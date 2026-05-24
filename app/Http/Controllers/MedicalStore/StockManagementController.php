<?php

namespace App\Http\Controllers\MedicalStore;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineStock;
use Illuminate\Http\Request;

class StockManagementController extends Controller
{
    public function index()
    {
        $stock = MedicineStock::with('medicine')
            ->orderBy('expiry_date')
            ->paginate(20);
            
        return view('medical-store.stock.index', compact('stock'));
    }

    public function create()
    {
        $medicines = Medicine::all();
        return view('medical-store.stock.create', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,medicine_id',
            'batch_number' => 'required|unique:medicine_stock,batch_number',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'manufacturing_date' => 'required|date',
            'expiry_date' => 'required|date|after:manufacturing_date',
            'location' => 'nullable',
        ]);

        MedicineStock::create([
            'medicine_id' => $request->medicine_id,
            'batch_number' => $request->batch_number,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'manufacturing_date' => $request->manufacturing_date,
            'expiry_date' => $request->expiry_date,
            'location' => $request->location,
            'last_updated' => now(),
        ]);

        return redirect()->route('medical-store.stock.index')->with('success', 'Stock added successfully.');
    }

    public function edit($id)
    {
        $stock = MedicineStock::findOrFail($id);
        $medicines = Medicine::all();
        return view('medical-store.stock.edit', compact('stock', 'medicines'));
    }

    public function update(Request $request, $id)
    {
        $stock = MedicineStock::findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'location' => 'nullable',
        ]);

        $stock->update([
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'expiry_date' => $request->expiry_date,
            'location' => $request->location,
            'last_updated' => now(),
        ]);

        return redirect()->route('medical-store.stock.index')->with('success', 'Stock updated successfully.');
    }

    public function destroy($id)
    {
        $stock = MedicineStock::findOrFail($id);
        
        if ($stock->quantity > 0) {
            return back()->with('error', 'Cannot remove stock with positive quantity. Please reduce quantity to zero first.');
        }
        
        $stock->delete();
        return back()->with('success', 'Stock removed successfully.');
    }

    public function addStock(Request $request, $id)
    {
        $stock = MedicineStock::findOrFail($id);
        
        $request->validate([
            'additional_quantity' => 'required|integer|min:1',
        ]);

        $stock->quantity += $request->additional_quantity;
        $stock->last_updated = now();
        $stock->save();

        return back()->with('success', 'Stock quantity increased successfully.');
    }

    public function removeExpired($id)
    {
        $stock = MedicineStock::findOrFail($id);
        
        $stock->quantity = 0;
        $stock->last_updated = now();
        $stock->save();

        return back()->with('success', 'Expired stock removed successfully.');
    }

    public function lowStock()
{
    $lowstock = MedicineStock::with('medicine')
        ->where('quantity', '<', 10)
        ->where('quantity', '>', 0)
        ->whereDate('expiry_date', '>=', now())
        ->orderBy('quantity', 'asc')
        ->get();
        
    return view('medical-store.stock.low-stock', compact('lowstock'));
}

public function expiredStock()
{
    $expiredStock = MedicineStock::with('medicine')
        ->whereDate('expiry_date', '<', now())
        ->where('quantity', '>', 0)
        ->orderBy('expiry_date', 'asc')
        ->get();
        
    return view('medical-store.stock.expired', compact('expiredStock'));
}

    public function export()
    {
        // Implement Excel export
        return back()->with('success', 'Stock report exported.');
    }

    public function bulkRemoveExpired(Request $request)
{
    $stockIds = json_decode($request->stock_ids);
    
    if (empty($stockIds)) {
        return back()->with('error', 'No items selected.');
    }
    
    $removed = 0;
    foreach ($stockIds as $id) {
        $stock = MedicineStock::find($id);
        if ($stock && \Carbon\Carbon::parse($stock->expiry_date)->isPast()) {
            $stock->quantity = 0;
            $stock->last_updated = now();
            $stock->save();
            $removed++;
        }
    }
    
    return redirect()->route('medical-store.stock.expired')
        ->with('success', $removed . ' expired stock items removed successfully.');
}
}