<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'description'
    ];
    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }
}
