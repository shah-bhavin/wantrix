<?php

namespace App\Models;

use App\Enums\MessageStatus;
use App\Enums\MessageType;
use App\Models\MessageStatusHistory;
use App\Models\WhatsappAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    public const MAX_RETRIES = 3;

    protected $fillable = [
        'vendor_id',
        'campaign_id',
        'contact_id',
        'body',
        'type',
        'status',
        'provider_message_id',
        'sent_at',
        'delivered_at',
        'read_at',
        'failure_reason',
        'retry_count',
        'last_retried_at',
        'whatsapp_template_id',
        'template_variables',

    ];

    protected $casts = [
        'status' => MessageStatus::class,
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'last_retried_at' => 'datetime',
        'type' => MessageType::class,
        'template_variables' => 'array',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
    public function canRetry(): bool
    {
        return $this->status === MessageStatus::FAILED
            && $this->retry_count < self::MAX_RETRIES;
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(MessageStatusHistory::class);
    }

    public function whatsappAccount()
    {
        return $this->belongsTo(
            WhatsappAccount::class
        );
    }
    public function getRecipientPhone(): ?string
    {
        return $this->contact?->phone_number;
    }
    public function whatsappTemplate()
    {
        return $this->belongsTo(
            WhatsappTemplate::class
        );
    }
}
