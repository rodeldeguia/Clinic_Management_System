<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'action', 'table_affected', 'record_id_affected', 'ip_address'
    ];

    protected $casts = [
        'timestamps' => 'datetime',  // Note: column name is 'timestamps' (plural)
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function scopeLatest($query)
{
    return $query->orderBy('timestamps', 'desc');
}
}