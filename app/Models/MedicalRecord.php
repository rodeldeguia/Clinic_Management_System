<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $fillable = [
        'appointment_id', 'doctor_id', 'diagnosis', 'prescription_text', 'notes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'user_id')
                    ->where('role', 'Doctor');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'record_id', 'record_id');
    }
}