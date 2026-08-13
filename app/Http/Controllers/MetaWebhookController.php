<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use App\Services\Webhooks\MetaWebhookDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetaWebhookController extends Controller
{
    public function verify(Request $request)
    {
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        if (
            $mode === 'subscribe' &&
            $token === config('meta.webhook_verify_token')
        ) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();

        $event = data_get(
            $payload,
            'entry.0.changes.0.field',
            'unknown'
        );

        $phoneNumberId = data_get(
            $payload,
            'entry.0.changes.0.value.metadata.phone_number_id'
        );

        $messages = data_get(
            $payload,
            'entry.0.changes.0.value.messages',
            []
        );

        $statuses = data_get(
            $payload,
            'entry.0.changes.0.value.statuses',
            []
        );

        /*
|--------------------------------------------------------------------------
| Extract Meta message status errors
|--------------------------------------------------------------------------
|
| Do NOT log the complete deeply nested payload here.
| Laravel's logger can normalize deeply nested Meta payloads and replace
| important values with:
|
| "Over 9 levels deep, aborting normalization"
|
| Instead, extract the important fields explicitly.
|
*/

        $statusErrors = [];

        foreach ($statuses as $status) {

            foreach (($status['errors'] ?? []) as $error) {

                $statusErrors[] = [
                    'message_id' => $status['id'] ?? null,
                    'status' => $status['status'] ?? null,
                    'recipient_id' => $status['recipient_id'] ?? null,

                    'error_code' => $error['code'] ?? null,
                    'error_title' => $error['title'] ?? null,
                    'error_message' => $error['message'] ?? null,

                    'error_data_details' =>
                    data_get($error, 'error_data.details'),

                    'error_data' => $error['error_data'] ?? null,
                ];
            }
        }

        Log::info('META WEBHOOK RECEIVED', [
            'event' => $event,
            'phone_number_id' => $phoneNumberId,
            'message_count' => count($messages),
            'status_count' => count($statuses),
            'status_errors' => $statusErrors,
        ]);

        WebhookLog::create([
            'provider' => 'meta',
            'event' => $event,
            'payload' => $payload,
        ]);

        try {
            app(MetaWebhookDispatcher::class)
                ->dispatch($payload);

            Log::info('META WEBHOOK PROCESSED', [
                'event' => $event,
                'phone_number_id' => $phoneNumberId,
                'message_count' => count($messages),
            ]);
        } catch (\Throwable $e) {

            Log::error('META WEBHOOK PROCESSING FAILED', [
                'event' => $event,
                'phone_number_id' => $phoneNumberId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Webhook processing failed.',
            ], 500);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
