<?php

namespace App\Services\Meta;

class MetaWebhookSignatureService
{
    public function verify(
        string $payload,
        ?string $signature
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Signature must be present
        |--------------------------------------------------------------------------
        */

        if (blank($signature)) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Signature must use SHA-256
        |--------------------------------------------------------------------------
        |
        | Meta sends:
        |
        | X-Hub-Signature-256: sha256=...
        |
        */

        if (! str_starts_with($signature, 'sha256=')) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Meta App Secret
        |--------------------------------------------------------------------------
        */

        $appSecret = config('meta.app_secret');

        if (blank($appSecret)) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Extract provided signature
        |--------------------------------------------------------------------------
        */

        $providedSignature = substr(
            $signature,
            7
        );

        if (blank($providedSignature)) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate expected signature
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | The HMAC must be calculated using the RAW webhook body.
        |
        */

        $expectedSignature = hash_hmac(
            'sha256',
            $payload,
            $appSecret
        );

        /*
        |--------------------------------------------------------------------------
        | Secure comparison
        |--------------------------------------------------------------------------
        */

        return hash_equals(
            $expectedSignature,
            $providedSignature
        );
    }
}