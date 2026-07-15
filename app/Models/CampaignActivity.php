<?php

namespace App\Models;

use App\Enums\CampaignActivityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignActivity extends Model
{
    protected $fillable = [
        'campaign_id',
        'type',
        'description',
    ];

    protected $casts = [
        'type' => CampaignActivityType::class,
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
