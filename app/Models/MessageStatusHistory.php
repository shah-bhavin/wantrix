<?php

namespace App\Models;

use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageStatusHistory extends Model
{
    protected $fillable = [
        'message_id',
        'status',
        'changed_at',
    ];

    protected $casts = [
        'status' => MessageStatus::class,
        'changed_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}