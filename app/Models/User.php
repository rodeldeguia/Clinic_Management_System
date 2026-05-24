<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $timestamps = false;
    
    protected $fillable = [
        'username', 
        'password_hashed', 
        'role', 
        'is_active', 
        'last_login',
        'firstname', 
        'lastname', 
        'contact_number', 
        'email_address',
        'profile_photo',
        'address', 
        'date_of_birth', 
        'gender',
        'specialization', 
        'license_number',
        'shift_timing', 
        'assigned_section',
        'blood_group', 
        'emergency_contact', 
        'registration_date',
        'store_role'
    ];

    protected $hidden = ['password_hashed'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'date_of_birth' => 'date',
        'registration_date' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->password_hashed;
    }

    // Role checks (using lowercase values)
    public function isAdmin() { return $this->role === 'admin'; }
    public function isDoctor() { return $this->role === 'doctor'; }
    public function isReceptionist() { return $this->role === 'receptionist'; }
    public function isPatient() { return $this->role === 'patient'; }
    public function isMedicalStore() { return $this->role === 'medical_store'; }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    // Relationships
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'user_id');
    }

    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'user_id');
    }

    public function createdAppointments()
    {
        return $this->hasMany(Appointment::class, 'created_by', 'user_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id', 'user_id');
    }

    public function dispensedPrescriptions()
    {
        return $this->hasMany(Prescription::class, 'dispensed_by', 'user_id');
    }

    public function generatedBills()
    {
        return $this->hasMany(Billing::class, 'generated_by', 'user_id');
    }

    public function receivedFeedback()
    {
        return $this->hasMany(Feedback::class, 'doctor_id', 'user_id');
    }

    public function givenFeedback()
    {
        return $this->hasMany(Feedback::class, 'patient_id', 'user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'user_id');
    }

    public function doctorSchedules()
{
    return $this->hasMany(DoctorSchedule::class, 'doctor_id', 'user_id');
}
}