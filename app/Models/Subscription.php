<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'cancelled_at',
        'created_by',
    ];

    protected $casts = [

        'status' => SubscriptionStatus::class,

        'starts_at' => 'datetime',

        'ends_at' => 'datetime',

        'trial_ends_at' => 'datetime',

        'cancelled_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function hasActiveSubscription(int $vendorId): bool
    {
        return self::query()
            ->where('vendor_id', $vendorId)
            ->whereIn('status', [
                'trial',
                'active',
            ])
            ->exists();
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
