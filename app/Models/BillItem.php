<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'bill_item_id';
    public $timestamps = false;

    protected $fillable = [
        'bill_id', 'item_type', 'item_description', 'item_reference_id',
        'quantity', 'unit_price', 'amount'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function bill()
    {
        return $this->belongsTo(Billing::class, 'bill_id', 'bill_id');
    }
}