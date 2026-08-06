<?php
namespace App\Services\Whatsapp\Providers;

use App\Models\Message;
use App\Services\Whatsapp\Contracts\WhatsappProvider;

class DummyProvider implements WhatsappProvider
{
    public function send(Message $message): array
    {
        if ($message->id % 3 === 0) {

            return [

                'success'=>false,

                'error'=>'Simulated provider failure.'

            ];
        }

        return [

            'success'=>true,

            'message_id'=>fake()->uuid()

        ];
    }

    public function verifyConnection(array $credentials): array
    {
        return [
            'success'=>true
        ];
    }

    public function subscribeWebhook(array $credentials): array
    {
        return [
            'success'=>true
        ];
    }
}