<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappAccount extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'phone_number',
        'country_code',
        'status',
        'waba_id',
        'phone_number_id',
        'business_id',
    ];    
    protected $casts = [
        'connected_at' => 'datetime',
    ];
}
