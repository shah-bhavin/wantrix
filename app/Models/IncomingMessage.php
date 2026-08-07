<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingMessage extends Model
{
    protected $fillable = [
        'vendor_id',
        'contact_id',
        'whatsapp_account_id',
        'conversation_id',
        'media_library_id',
        'provider_message_id',
        'from_phone',
        'body',
        'type',
        'payload',
        'received_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'received_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function whatsappAccount()
    {
        return $this->belongsTo(WhatsappAccount::class);
    }

    public function media()
    {
        return $this->belongsTo(
            MediaLibrary::class,
            'media_library_id'
        );
    }
}