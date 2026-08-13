<?php

namespace App\Actions\Meta;

use App\Models\Message;
use Illuminate\Support\Facades\Log;

class ProcessWebhookAction
{
    public function execute(array $payload): void
    {
        $value = $payload['entry'][0]['changes'][0]['value'] ?? [];

        $statuses = $value['statuses'] ?? [];

        foreach ($statuses as $status) {

            $providerMessageId = $status['id'] ?? null;

            if (blank($providerMessageId)) {
                continue;
            }

            $message = Message::where(
                'provider_message_id',
                $providerMessageId
            )->first();

            /*
            |--------------------------------------------------------------------------
            | Extract Meta error
            |--------------------------------------------------------------------------
            */

            $metaError = $status['errors'][0] ?? null;

            if ($metaError) {

                $errorCode = $metaError['code'] ?? null;

                $errorTitle = $metaError['title'] ?? null;

                $errorMessage = $metaError['message'] ?? null;

                $errorDetails =
                    data_get(
                        $metaError,
                        'error_data.details'
                    );

                Log::error(
                    'WHATSAPP MESSAGE DELIVERY FAILED',
                    [
                        'provider_message_id' =>
                        $providerMessageId,

                        'recipient_id' =>
                        $status['recipient_id'] ?? null,

                        'error_code' =>
                        $errorCode,

                        'error_title' =>
                        $errorTitle,

                        'error_message' =>
                        $errorMessage,

                        'error_details' =>
                        $errorDetails,
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Update our local message if it exists
                |--------------------------------------------------------------------------
                */

                if ($message) {

                    $message->update([
                        'status' => 'failed',
                    ]);
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Normal message status
            |--------------------------------------------------------------------------
            */

            if (!$message) {
                continue;
            }

            switch ($status['status'] ?? null) {

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
