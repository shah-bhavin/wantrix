<?php

namespace App\Services\Meta;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MetaApiService
{
    /**
     * Graph API Base URL
     *
     * Example:
     * https://graph.facebook.com/v25.0
     */
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('meta.graph_url'),
            '/'
        ) . '/' . config('meta.graph_version');
    }

    /**
     * Create an authenticated HTTP client.
     */
    protected function client(string $token): PendingRequest
    {
        return Http::withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(30);
    }

    /**
     * Verify that the Access Token and Phone Number ID are valid.
     *
     * (Implementation will be added in the next step.)
     */
    public function verifyConnection(string $phoneNumberId, string $accessToken): array
    {

        $response = $this->client($accessToken)
            ->get(
                "{$this->baseUrl}/{$phoneNumberId}",
                [
                    'fields' => implode(',', [
                        'verified_name',
                        'display_phone_number',
                        'quality_rating',
                        'code_verification_status',
                        'id',
                    ]),
                ]
            );

        if ($response->failed()) {

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json(),
        ];
    }

    // public function discoverWaba(string $phoneNumberId, string $accessToken): array {

    //     $response = $this->client($accessToken)
    //         ->get(
    //             "{$this->baseUrl}/{$phoneNumberId}",
    //             [
    //                 'fields' => 'whatsapp_business_account'
    //             ]
    //         );

    //     if ($response->failed()) {

    //         return [
    //             'success' => false,
    //             'status' => $response->status(),
    //             'error' => $response->json(),
    //         ];
    //     }

    //     return [
    //         'success' => true,
    //         'data' => $response->json(),
    //     ];
    // }

    public function discoverWaba(string $phoneNumberId, string $accessToken): array
    {
        $response = $this->client($accessToken)
            ->get(
                "{$this->baseUrl}/{$phoneNumberId}",
                [
                    'fields' => 'whatsapp_business_account{id,name}'
                ]
            );

        dd($response->json());
    }

    /**
     * Get Phone Number Details.
     */
    public function getPhoneNumber(string $phoneNumberId, string $accessToken): array
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    /**
     * Send a template message.
     */
    public function sendTemplate(array $payload): array
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    /**
     * Send a text message.
     */
    public function sendText(array $payload): array
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    /**
     * Upload media.
     */
    public function uploadMedia(array $payload): array
    {
        throw new \RuntimeException('Not implemented yet.');
    }
}
