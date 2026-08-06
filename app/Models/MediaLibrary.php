<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaLibrary extends Model
{
    protected $fillable = [

        'vendor_id',

        'name',

        'original_name',

        'disk',

        'path',

        'mime_type',

        'size',

        'type',

        'meta_media_id',

        'uploaded_to_meta',

        'uploaded_at',

    ];

    protected $casts = [
        'type' => MediaType::class,

        'uploaded_to_meta' => 'boolean',

        'uploaded_at' => 'datetime',

    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
