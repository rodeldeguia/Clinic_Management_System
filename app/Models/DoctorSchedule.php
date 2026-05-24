<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $table = 'doctor_schedules';  // Your table name
    protected $primaryKey = 'schedule_id';
    public $timestamps = false;

    protected $fillable = [
        'doctor_id', 
        'day_of_week', 
        'start_time', 
        'end_time', 
        'is_available', 
        'slot_duration'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'user_id');
    }
}