<?php

namespace App\Actions\Meta;

use App\Models\Message;

class ProcessWebhookAction
{
    public function execute(array $payload): void
    {
        $statuses = $payload['entry'][0]['changes'][0]['value']['statuses'] ?? [];

        foreach ($statuses as $status) {
            $message = Message::where('provider_message_id', $status['id'])->first();

            if (!$message) {
                continue;
            }

            switch ($status['status']) {
                case 'sent':
                    $message->update([
                        'status' => 'sent',
                    ]);
                    break;

                case 'delivered':
                    $message->update([
                        'status' => 'delivered',
                        'delivered_at' => now(),
                    ]);
                    break;

                case 'read':
                    $message->update([
                        'status' => 'read',
                        'read_at' => now(),
                    ]);
                    break;

                case 'failed':
                    $message->update([
                        'status' => 'failed',
                    ]);
                    break;
            }
        }
    }
}
