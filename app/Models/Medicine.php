<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $primaryKey = 'medicine_id';
    public $timestamps = false;

    protected $fillable = [
        'medicine_name', 'category', 'manufacturer', 'description'
    ];

    public function stockEntries()
    {
        return $this->hasMany(MedicineStock::class, 'medicine_id', 'medicine_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'medicine_id', 'medicine_id');
    }

    public function getTotalStockAttribute()
    {
        return $this->stockEntries()->sum('quantity');
    }
}