<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'amount',
        'method',
        'payment_date',
        'notes',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
