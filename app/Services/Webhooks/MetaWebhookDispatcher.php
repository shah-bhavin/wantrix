<?php

namespace App\Services\Webhooks;

use App\Actions\Webhooks\ProcessIncomingMessageAction;
use App\Actions\Webhooks\ProcessStatusWebhookAction;
use Illuminate\Support\Facades\Log;

class MetaWebhookDispatcher
{
    public function dispatch(array $payload): void
    {
        /*
        |--------------------------------------------------------------------------
        | Process every entry
        |--------------------------------------------------------------------------
        */

        foreach ($payload['entry'] ?? [] as $entry) {

            foreach ($entry['changes'] ?? [] as $change) {

                $value = $change['value'] ?? [];

                $field = $change['field'] ?? 'unknown';

                /*
                |--------------------------------------------------------------------------
                | Status Updates
                |--------------------------------------------------------------------------
                */

                if (! empty($value['statuses'])) {

                    foreach ($value['statuses'] as $status) {

                        app(ProcessStatusWebhookAction::class)
                            ->execute($status);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Incoming Messages
                |--------------------------------------------------------------------------
                */

                if (! empty($value['messages'])) {

                    foreach ($value['messages'] as $message) {

                        app(ProcessIncomingMessageAction::class)
                            ->execute(
                                $value,
                                $message
                            );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Dispatcher Log
                |--------------------------------------------------------------------------
                |
                | Log only useful summary information.
                | Never log the complete Meta payload here.
                |
                */

                Log::info('META WEBHOOK DISPATCHED', [

                    'field' => $field,

                    'phone_number_id' =>
                        data_get(
                            $value,
                            'metadata.phone_number_id'
                        ),

                    'message_count' =>
                        count($value['messages'] ?? []),

                    'status_count' =>
                        count($value['statuses'] ?? []),

                ]);
            }
        }
    }
}