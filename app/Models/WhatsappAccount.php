<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappAccount extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'phone_number',
        'provider',
        'country_code',
        'status',
        'waba_id',
        'phone_number_id',
        'access_token',
        'is_active',
        'business_id',
    ];
    protected $casts = [
        'connected_at' => 'datetime',
        'access_token' => 'encrypted',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
