<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PlanStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'yearly_price',
        'max_users',
        'max_contacts',
        'max_whatsapp_numbers',
        'max_campaigns_per_month',
        'is_popular',
        'status',
        'sort_order',
        'trial_days',
        'is_unlimited_contacts',
        'is_unlimited_whatsapp_numbers',
        'is_unlimited_campaigns',
        'is_unlimited_users',
        'max_contacts'
    ];

    protected $casts = [
        'status' => PlanStatus::class,
        'is_popular' => 'boolean',
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'is_unlimited_users' => 'boolean',
        'is_unlimited_contacts' => 'boolean',
        'is_unlimited_whatsapp_numbers' => 'boolean',
        'is_unlimited_campaigns' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public static function defaultPlan(): ?self
    {
        return self::query()
            ->where('is_default', true)
            ->first();
    }
    public function oldSubscriptionChanges()
    {
        return $this->hasMany(SubscriptionChange::class, 'old_plan_id');
    }

    public function newSubscriptionChanges()
    {
        return $this->hasMany(SubscriptionChange::class, 'new_plan_id');
    }
}
