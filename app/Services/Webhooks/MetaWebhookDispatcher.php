<?php

namespace App\Services\Webhooks;

use App\Actions\Webhooks\ProcessIncomingMessageAction;
use App\Actions\Webhooks\ProcessStatusWebhookAction;
use Illuminate\Support\Facades\Log;

class MetaWebhookDispatcher
{
    public function dispatch(array $payload): void
    {
        Log::info('Meta Webhook Received', $payload);

        // Process every entry
        foreach ($payload['entry'] ?? [] as $entry) {

            foreach ($entry['changes'] ?? [] as $change) {

                $value = $change['value'] ?? [];

                /*
                 |--------------------------------------------------------------------------
                 | Status Updates
                 |--------------------------------------------------------------------------
                 */

                if (!empty($value['statuses'])) {

                    foreach ($value['statuses'] as $status) {

                        app(ProcessStatusWebhookAction::class)->execute($status);

                    }

                }

                /*
                 |--------------------------------------------------------------------------
                 | Incoming Messages
                 |--------------------------------------------------------------------------
                 */

                if (!empty($value['messages'])) {

                    foreach ($value['messages'] as $message) {

                        app(ProcessIncomingMessageAction::class)->execute($value, $message);

                    }

                }

            }

        }
    }
}