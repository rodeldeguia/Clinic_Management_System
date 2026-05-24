<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'appointment_id';
    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_date', 'time_slot',
        'status', 'reason_for_visit', 'created_by', 'cancellation_reason'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'created_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'appointment_id', 'appointment_id');
    }

    public function billing()
    {
        return $this->hasOne(Billing::class, 'appointment_id', 'appointment_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'appointment_id', 'appointment_id');
    }
}