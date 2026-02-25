<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'name',
        'price',
        'quantity',
        'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer',
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
