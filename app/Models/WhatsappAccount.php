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
        'display_phone_number',
        'verified_name',
        'webhook_verify_token',
        'metadata',
        'quality_rating',
        'verification_status',
        'connected_at',
        'last_verified_at',
        'last_error',
    ];
    protected $casts = [
        'connected_at' => 'datetime',
        'last_verified_at' => 'datetime',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'access_token' => 'encrypted',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function incomingMessages()
    {
        return $this->hasMany(IncomingMessage::class);
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
