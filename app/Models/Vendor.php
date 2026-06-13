<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\VendorStatus;

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
}
