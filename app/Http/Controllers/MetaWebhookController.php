<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use Illuminate\Http\Request;

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

    public function receive(Request $request)
    {
        $payload = $request->all();

        $event = data_get(
            $payload,
            'entry.0.changes.0.field',
            'unknown'
        );

        WebhookLog::create([
            'provider'  => 'meta',
            'event'     => $event,
            'payload'   => $payload,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
