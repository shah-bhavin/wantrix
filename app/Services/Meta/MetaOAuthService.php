<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class MetaOAuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function exchangeCode(string $code): array
    {
        $response = Http::acceptJson()->get(
            config('meta.graph_url') . '/'
            . config('meta.graph_version')
            . '/oauth/access_token',
            [
                'client_id' => config('meta.app_id'),
                'client_secret' => config('meta.app_secret'),
                'code' => $code,
            ]
        );

        if ($response->failed()) {

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json(),
        ];
    }
}
