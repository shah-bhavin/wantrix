<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class MetaWebhookController extends Controller
{
    public function verify(Request $request)
    {
        $verifyToken = config('services.meta.verify_token');

        if ($request->get('hub_verify_token') === $verifyToken) {
            return response($request->get('hub_challenge'), 200);
        }

        return response('Invalid verify token.', 403);
    }

    public function webhook(Request $request)
    {
        Log::channel('meta')
        ->info('Meta Webhook', $request->all());

        app(\App\Actions\Meta\ProcessWebhookAction::class)->execute($request->all());

        return response()->json([
            'success' => true,
        ]);

    }
}
