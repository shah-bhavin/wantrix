<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsappTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'meta_template_id',
        'name',
        'language',
        'category',
        'status',
        'quality_score',
        'allow_category_change',
        'components',
        'raw_response',
        'last_synced_at',
    ];

    protected $casts = [
        'allow_category_change' => 'boolean',

        'components' => 'array',

        'raw_response' => 'array',

        'last_synced_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isApproved(): bool
    {
        return $this->status === 'APPROVED';
    }

    public function isRejected(): bool
    {
        return $this->status === 'REJECTED';
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
