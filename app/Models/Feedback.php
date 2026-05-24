<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'feedback_id';
    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'appointment_id', 'doctor_id', 'rating', 
        'comments', 'is_public'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_public' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'user_id')
                    ->where('role', 'Patient');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'user_id')
                    ->where('role', 'Doctor');
    }
}