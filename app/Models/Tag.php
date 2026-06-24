<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'color',
    ];

    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

}
