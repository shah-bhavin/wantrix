<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'vendor_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'currency',
        'status',
        'issued_at',
        'paid_at',
    ];
    protected $casts = [
        'issued_at' => 'datetime', // Or 'date'
        'paid_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
