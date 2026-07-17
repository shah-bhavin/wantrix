<?php

namespace App\Services;

use App\Models\Message;

class WhatsAppService
{
    public function send(Message $message): array
    {
        if ($message->id % 3 === 0) {
            return [
                'success' => false,
                'error' => 'Simulated provider failure.',
            ];
        }

        return [
            'success' => true,
            'message_id' => fake()->uuid(),
        ];
    }
}
