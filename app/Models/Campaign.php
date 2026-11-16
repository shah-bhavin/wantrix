<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Enums\MessageType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'vendor_id',
        'group_id',
        'template_id',
        'whatsapp_template_id',
        'whatsapp_account_id',
        'name',
        'status',
        'messages_generated_at',
        'message_delay_seconds',
        'message_type',
        'message_body',

        'media_library_id',
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
        'message_type' => MessageType::class,
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

    /*
     * Legacy local template relationship.
     * Kept temporarily for backward compatibility
     * with existing campaigns.
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /*
     * Meta / WhatsApp approved template used
     * for new campaign messages.
     */
    public function whatsappTemplate()
    {
        return $this->belongsTo(
            WhatsappTemplate::class,
            'whatsapp_template_id'
        );
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
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

    public function canGenerateMessages(): bool
    {
        return
            $this->status === CampaignStatus::DRAFT
            && ! $this->messages()->exists();
    }

    public function canDeleteMessages(): bool
    {
        return $this->status === CampaignStatus::DRAFT;
    }

    public function canSend(): bool
    {
        return
            $this->status->canDispatch()
            && $this->messages()->exists();
    }

    public function canPause(): bool
    {
        return $this->status === CampaignStatus::PROCESSING;
    }

    public function canResume(): bool
    {
        return $this->status === CampaignStatus::PAUSED;
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

    public function canBeProcessed(): bool
    {
        return
            $this->status === CampaignStatus::DRAFT
            && $this->messages_generated_at !== null
            && $this->messages()->exists();
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
