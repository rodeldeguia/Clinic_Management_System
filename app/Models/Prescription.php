<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $primaryKey = 'prescription_id';
    public $timestamps = false;  // ← Make sure this is here

    protected $fillable = [
        'record_id', 
        'medicine_id', 
        'dosage', 
        'quantity_prescribed',
        'quantity_dispensed', 
        'status', 
        'dispensed_by', 
        'dispensed_at'
    ];

    protected $casts = [
        'dispensed_at' => 'datetime',
    ];


    // Use this to order by prescription_id instead of created_at
    public function scopeLatest($query)
    {
        return $query->orderBy('prescription_id', 'desc');
    }

    public function billingItems()
{
    return $this->hasMany(BillItem::class, 'item_reference_id', 'prescription_id')
        ->where('item_type', 'medicine');
}

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'record_id', 'record_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'medicine_id');
    }

    public function dispensedBy()
    {
        return $this->belongsTo(User::class, 'dispensed_by', 'user_id');
    }
}