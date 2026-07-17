<?php

namespace App\Models;

use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public const MAX_RETRIES = 3;
    protected $fillable = [
        'vendor_id',
        'campaign_id',
        'contact_id',
        'body',
        'status',
        'provider_message_id',
        'sent_at',
        'delivered_at',
        'read_at',
        'failure_reason',
        'retry_count',
        'last_retried_at',
    ];

    protected $casts = [
        'status' => MessageStatus::class,
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'last_retried_at' => 'datetime',
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
            && $this->retry_count < 3;
    }
}
