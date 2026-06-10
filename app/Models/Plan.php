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
    ];

    protected $casts = [
        'status' => PlanStatus::class,
        'is_popular' => 'boolean',
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
    ];

}
