<?php

namespace App\Models;

use App\Enums\VendorStatus;
use App\Observers\VendorObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'gst_number',
        'address',
        'logo',
        'website',
        'city',
        'state',
        'country',
        'postal_code',
        'gst_number',
        'timezone',
        'status',
        'created_by',
    ];
    protected $casts = [
        'status' => VendorStatus::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function subscriptionChanges()
    {
        return $this->hasMany(SubscriptionChange::class);
    }
    public function whatsappAccounts()
    {
        return $this->hasMany(WhatsappAccount::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();
    }

    public function activePlan()
    {
        return $this->activeSubscription()?->plan;
    }
}
