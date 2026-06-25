<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'phone_number',
        'email',
        'company',
        'city',
        'state',
        'country',
        'notes',
        'status',
        'last_contacted_at',
    ];
    
    protected $casts = [
        'last_contacted_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }


}
