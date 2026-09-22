<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use App\Services\Meta\MetaWebhookSignatureService;
use App\Services\Webhooks\MetaWebhookDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetaWebhookController extends Controller
{
    public function __construct(
        protected MetaWebhookSignatureService $signatureService,
    ) {}
    /**
     * --------------------------------------------------------------------------
     * META WEBHOOK VERIFICATION
     * --------------------------------------------------------------------------
     *
     * Meta sends:
     *
     * hub.mode
     * hub.verify_token
     * hub.challenge
     *
     * We must return hub.challenge when the token matches.
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        Log::info('META WEBHOOK VERIFICATION REQUEST', [
            'mode' => $mode,
            'token_received' => filled($token),
            'challenge_received' => filled($challenge),
        ]);

        if (
            $mode === 'subscribe'
            && hash_equals(
                (string) config('meta.webhook.verify_token'),
                (string) $token
            )
        ) {
            Log::info('META WEBHOOK VERIFICATION SUCCESS');

            return response($challenge, 200);
        }

        Log::warning('META WEBHOOK VERIFICATION FAILED');

        return response('Forbidden', 403);
    }


    /**
     * --------------------------------------------------------------------------
     * META WEBHOOK RECEIVER
     * --------------------------------------------------------------------------
     */
    public function webhook(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Step 1: Get raw payload
        |--------------------------------------------------------------------------
        */

        $rawPayload = $request->getContent();

        $signature = $request->header(
            'X-Hub-Signature-256'
        );

        if (! $this->signatureService->verify(
            $rawPayload,
            $signature
        )) {
            Log::warning('META WEBHOOK SIGNATURE INVALID', [
                'signature_present' => ! empty($signature),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Invalid webhook signature.',
            ], 401);
        }

        $payload = json_decode(
            $rawPayload,
            true
        );

        if (! is_array($payload)) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid JSON payload.',
            ], 400);
        }


        /*
        |--------------------------------------------------------------------------
        | Step 2: Identify webhook event
        |--------------------------------------------------------------------------
        */

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
        | Step 3: Log important information
        |--------------------------------------------------------------------------
        |
        | Do NOT dump the complete deeply nested payload into Laravel logs.
        |
        */

        $statusErrors = [];

        foreach ($statuses as $status) {

            foreach (($status['errors'] ?? []) as $error) {

                $statusErrors[] = [

                    'message_id' =>
                    $status['id'] ?? null,

                    'status' =>
                    $status['status'] ?? null,

                    'recipient_id' =>
                    $status['recipient_id'] ?? null,

                    'error_code' =>
                    $error['code'] ?? null,

                    'error_title' =>
                    $error['title'] ?? null,

                    'error_message' =>
                    $error['message'] ?? null,

                    'error_data_details' =>
                    data_get(
                        $error,
                        'error_data.details'
                    ),

                ];
            }
        }

        Log::info('META WEBHOOK RECEIVED', [

            'event' => $event,

            'phone_number_id' =>
            $phoneNumberId,

            'message_count' =>
            count($messages),

            'status_count' =>
            count($statuses),

            'status_errors' =>
            $statusErrors,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Step 4: Save webhook log
        |--------------------------------------------------------------------------
        */

        $webhookLog = WebhookLog::create([

            'provider' => 'meta',

            'event' => $event,

            'payload' => $payload,

            'processed' => false,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Step 5: Dispatch webhook
        |--------------------------------------------------------------------------
        */

        try {

            app(MetaWebhookDispatcher::class)
                ->dispatch($payload);


            /*
            |--------------------------------------------------------------------------
            | Mark webhook as successfully processed
            |--------------------------------------------------------------------------
            */

            $webhookLog->update([

                'processed' => true,

                'processed_at' => now(),

            ]);


            Log::info('META WEBHOOK PROCESSED', [

                'webhook_log_id' =>
                $webhookLog->id,

                'event' =>
                $event,

                'phone_number_id' =>
                $phoneNumberId,

                'message_count' =>
                count($messages),

                'status_count' =>
                count($statuses),

            ]);
        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Keep processed = false
            |--------------------------------------------------------------------------
            |
            | This is useful because later we can build webhook retry/reprocessing
            | functionality around WebhookLog.
            |
            */

            Log::error('META WEBHOOK PROCESSING FAILED', [

                'webhook_log_id' =>
                $webhookLog->id,

                'event' =>
                $event,

                'phone_number_id' =>
                $phoneNumberId,

                'error' =>
                $e->getMessage(),

                'trace' =>
                $e->getTraceAsString(),

            ]);

            return response()->json([

                'success' => false,

                'error' =>
                'Webhook processing failed.',

            ], 500);
        }


        /*
        |--------------------------------------------------------------------------
        | Step 6: Respond to Meta
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

        ], 200);
    }
}
