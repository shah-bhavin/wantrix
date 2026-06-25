<?php

namespace App\Services;

use App\Models\Message;

class WhatsAppService
{
    public function send(Message $message): array
    {
        /*
         |--------------------------------------------------------------------------
         | Fake Response
         |--------------------------------------------------------------------------
         |
         | Later this method will call Meta API.
         |
         */

        return [
            'success' => true,
            'message_id' => fake()->uuid(),
        ];
    }
}
