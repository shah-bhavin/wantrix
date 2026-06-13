<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionChange extends Model
{
    protected $fillable = [
        'vendor_id',
        'old_subscription_id',
        'new_subscription_id',
        'old_plan_id',
        'new_plan_id',
        'change_type',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'change_type' => SubscriptionChangeType::class,
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function oldSubscription()
    {
        return $this->belongsTo(Subscription::class, 'old_subscription_id');
    }

    public function newSubscription()
    {
        return $this->belongsTo(Subscription::class, 'new_subscription_id');
    }

    public function oldPlan()
    {
        return $this->belongsTo(Plan::class, 'old_plan_id');
    }

    public function newPlan()
    {
        return $this->belongsTo(Plan::class, 'new_plan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
