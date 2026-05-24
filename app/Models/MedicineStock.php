<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineStock extends Model
{
    use HasFactory;

    protected $table = 'medicine_stocks';  // ← Your table name is plural
    protected $primaryKey = 'stock_id';
    public $timestamps = false;

    protected $fillable = [
        'medicine_id', 'batch_number', 'quantity', 'unit_price',
        'manufacturing_date', 'expiry_date', 'location'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'medicine_id');
    }

    public function isExpired()
    {
        return $this->expiry_date < now();
    }

    public function isLowStock()
    {
        return $this->quantity < 10;
    }
}