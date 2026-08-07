<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [

        'vendor_id',

        'contact_id',

        'whatsapp_account_id',

        'last_message_at',

        'last_incoming_at',

        'last_outgoing_at',

        'unread_count',

        'is_closed',

        'is_ai_enabled',

    ];

    protected $casts = [

        'last_message_at' => 'datetime',

        'last_incoming_at' => 'datetime',

        'last_outgoing_at' => 'datetime',

        'is_closed' => 'boolean',

        'is_ai_enabled' => 'boolean',

    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function whatsappAccount()
    {
        return $this->belongsTo(WhatsappAccount::class);
    }

    public function incomingMessages()
    {
        return $this->hasMany(IncomingMessage::class);
    }

    public function outgoingMessages()
    {
        return $this->hasMany(Message::class);
    }

    public function touchIncoming(): void
    {
        $this->update([
            'last_message_at' => now(),
            'last_incoming_at' => now(),
        ]);

        $this->increment('unread_count');
    }

    public function touchOutgoing(): void
    {
        $this->update([
            'last_message_at' => now(),
            'last_outgoing_at' => now(),
        ]);
    }

    public function markRead(): void
    {
        $this->update([

            'unread_count' => 0,

        ]);
    }
}
