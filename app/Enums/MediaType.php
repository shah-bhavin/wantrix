<?php

namespace App\Enums;

enum MediaType: string
{
    case IMAGE = 'image';

    case VIDEO = 'video';

    case DOCUMENT = 'document';

    case AUDIO = 'audio';

    case STICKER = 'sticker';

    case UNKNOWN = 'unknown';

    public function label(): string
    {
        return match ($this) {

            self::IMAGE => 'Image',

            self::VIDEO => 'Video',

            self::DOCUMENT => 'Document',

            self::AUDIO => 'Audio',

            self::STICKER => 'Sticker',

            self::UNKNOWN => 'Unknown',

        };
    }

    public function acceptsPreview(): bool
    {
        return match ($this) {

            self::IMAGE,

            self::VIDEO => true,

            default => false,

        };
    }
}