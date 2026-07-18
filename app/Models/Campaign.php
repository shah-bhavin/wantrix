<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Models\CampaignActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'vendor_id',
        'group_id',
        'template_id',
        'whatsapp_account_id',
        'name',
        'status',
        'messages_generated_at',
        'message_delay_seconds',
        'scheduled_at',
        'started_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'status' => CampaignStatus::class,
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'messages_generated_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function whatsappAccount()
    {
        return $this->belongsTo(WhatsappAccount::class);
    }
    public function canGenerateMessages(): bool
    {
        return
            $this->status === CampaignStatus::DRAFT
            && is_null($this->messages_generated_at);
    }

    public function canDeleteMessages(): bool
    {
        return $this->status === CampaignStatus::DRAFT;
    }

    public function canSend(): bool
    {
        return
            $this->status === CampaignStatus::DRAFT
            && $this->messages_generated_at !== null;
    }

    public function canPause(): bool
    {
        return
            $this->status === CampaignStatus::PROCESSING;
    }

    public function canResume(): bool
    {
        return in_array($this->status, [
            CampaignStatus::PAUSED,
            CampaignStatus::CANCELLED,
        ]);
    }

    public function canCancel(): bool
    {
        return in_array(
            $this->status,
            [
                CampaignStatus::DRAFT,
                CampaignStatus::SCHEDULED,
                CampaignStatus::PROCESSING,
                CampaignStatus::PAUSED,
            ]
        );
    }
    public function isReadyToComplete(): bool
    {
        return ! $this->messages()
            ->whereIn('status', [
                MessageStatus::PENDING->value,
                MessageStatus::QUEUED->value,
                MessageStatus::SENDING->value,
            ])
            ->exists();
    }
    public function activities(): HasMany
    {
        return $this->hasMany(CampaignActivity::class);
    }
}
