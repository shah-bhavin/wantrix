<?php

namespace App\Enums;

enum MessageType: string
{
    case TEXT = 'text';

    case TEMPLATE = 'template';

    case IMAGE = 'image';

    case DOCUMENT = 'document';

    case VIDEO = 'video';

    case AUDIO = 'audio';

    case STICKER = 'sticker';

    case LOCATION = 'location';

    case CONTACT = 'contact';

    case INTERACTIVE = 'interactive';

    case BUTTONS = 'buttons';
}