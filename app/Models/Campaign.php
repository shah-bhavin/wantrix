<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'vendor_id',
        'group_id',
        'template_id',
        'whatsapp_account_id',
        'name',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'status' => CampaignStatus::class,
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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
        return
            $this->status === CampaignStatus::PAUSED;
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
}
