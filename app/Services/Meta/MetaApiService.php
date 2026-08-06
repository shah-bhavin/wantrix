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

    protected function get(string $accessToken, string $uri, array $query = []): array
    {

        $response = $this->client($accessToken)
            ->get(
                "{$this->baseUrl}{$uri}",
                $query
            );

        return $this->handleResponse($response);
    }

    protected function post(string $accessToken, string $uri, array $payload = []): array
    {

        $response = $this->client($accessToken)
            ->post(
                "{$this->baseUrl}{$uri}",
                $payload
            );

        return $this->handleResponse($response);
    }

    protected function handleResponse($response): array
    {
        if ($response->failed()) {

            return [

                'success' => false,

                'status' => $response->status(),

                'error' => $response->json(),
            ];
        }

        return [

            'success' => true,

            'status' => $response->status(),

            'data' => $response->json(),
        ];
    }

    protected function delete(string $accessToken, string $uri): array
    {

        $response = $this->client($accessToken)
            ->delete(
                "{$this->baseUrl}{$uri}"
            );

        return $this->handleResponse($response);
    }

    /**
     * Verify that the Access Token and Phone Number ID are valid.
     *
     * (Implementation will be added in the next step.)
     */
    public function verifyConnection(string $phoneNumberId, string $accessToken): array
    {

        return $this->getPhoneDetails(
            $phoneNumberId,
            $accessToken
        );
    }

    /**
     * Get Business Manager.
     */

    public function getBusinesses(string $accessToken): array
    {
        return $this->get(
            $accessToken,
            "/me/businesses"
        );
    }

    public function getOwnedWabas(string $businessId, string $accessToken): array
    {

        return $this->get(

            $accessToken,

            "/{$businessId}/owned_whatsapp_business_accounts"
        );
    }

    public function getPhoneNumbers(string $wabaId, string $accessToken): array
    {

        return $this->get(

            $accessToken,

            "/{$wabaId}/phone_numbers"
        );
    }

    public function getPhoneDetails(string $phoneNumberId, string $accessToken): array
    {

        return $this->get(

            $accessToken,

            "/{$phoneNumberId}",

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
    }

    public function getTemplates(
        string $wabaId,
        string $accessToken,
    ): array {

        return $this->get(

            $accessToken,

            "/{$wabaId}/message_templates"

        );
    }

    /**
     * Subscribe App to Webhook.
     */
    public function subscribeWebhook(string $wabaId, string $accessToken): array
    {
        return $this->post(

            $accessToken,

            "/{$wabaId}/subscribed_apps"

        );
    }

    /**
     * Exchange Embedded Signup code for token.
     */
    public function exchangeCode(string $code): array
    {
        $response = Http::asForm()->post(
            'https://graph.facebook.com/' . config('meta.graph_version') . '/oauth/access_token',
            [
                'client_id'     => config('meta.app_id'),
                'client_secret' => config('meta.app_secret'),
                'code'          => $code,
            ]
        );

        return $this->handleResponse($response);
    }

    public function sendText(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $body,
    ): array {

        $result = $this->post(

            $accessToken,

            "/{$phoneNumberId}/messages",

            [

                'messaging_product' => 'whatsapp',

                'recipient_type' => 'individual',

                'to' => $to,

                'type' => 'text',

                'text' => [

                    'preview_url' => false,

                    'body' => $body,

                ],

            ]

        );

        if (! $result['success']) {

            return $result;
        }

        return [

            'success' => true,

            'message_id' =>

            $result['data']['messages'][0]['id'] ?? null,

            'raw' => $result['data'],

        ];
    }

    /**
     * Send a template message.
     */
    public function sendTemplate(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $templateName,
        string $language = 'en',
        array $variables = [],
    ): array {

        $result = $this->post(

            $accessToken,

            "/{$phoneNumberId}/messages",

            [

                'messaging_product' => 'whatsapp',

                'to' => $to,

                'type' => 'template',

                'template' => [

                    'name' => $templateName,

                    'language' => [

                        'code' => $language,

                    ],

                    'components' => [

                        [

                            'type' => 'body',

                            'parameters' => collect($variables)

                                ->map(fn($value) => [

                                    'type' => 'text',

                                    'text' => (string) $value,

                                ])

                                ->values()

                                ->all(),

                        ],

                    ],

                ],

            ]

        );

        if (! $result['success']) {

            return $result;
        }

        return [

            'success' => true,

            'message_id' =>
            $result['data']['messages'][0]['id'] ?? null,

            'raw' => $result['data'],

        ];
    }

    public function sendInteractiveButtons(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $body,
        array $buttons,
    ): array {
        return $this->post(

            $accessToken,

            "/{$phoneNumberId}/messages",

            [

                'messaging_product' => 'whatsapp',

                'to' => $to,

                'type' => 'interactive',

                'interactive' => [

                    'type' => 'button',

                    'body' => [

                        'text' => $body,

                    ],

                    'action' => [

                        'buttons' => collect($buttons)
                            ->take(3)
                            ->values()
                            ->map(function ($button, $index) {

                                return [

                                    'type' => 'reply',

                                    'reply' => [

                                        'id' => 'btn_' . $index,

                                        'title' => $button,

                                    ],

                                ];
                            }),

                    ],

                ],

            ]

        );
    }

    public function sendImage(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $imageUrl,
    ): array {

        $result = $this->post(

            $accessToken,

            "/{$phoneNumberId}/messages",

            [

                'messaging_product' => 'whatsapp',

                'to' => $to,

                'type' => 'image',

                'image' => [

                    'link' => $imageUrl,

                ],

            ]

        );

        if (! $result['success']) {
            return $result;
        }

        return [

            'success' => true,

            'message_id' => $result['data']['messages'][0]['id'] ?? null,

            'raw' => $result['data'],

        ];
    }

    public function sendDocument(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $documentUrl,
        ?string $filename = null,
    ): array {

        $result = $this->post(

            $accessToken,

            "/{$phoneNumberId}/messages",

            [

                'messaging_product' => 'whatsapp',

                'to' => $to,

                'type' => 'document',

                'document' => [

                    'link' => $documentUrl,

                    'filename' => $filename,

                ],

            ]

        );

        if (! $result['success']) {
            return $result;
        }

        return [

            'success' => true,

            'message_id' =>

            $result['data']['messages'][0]['id'] ?? null,

            'raw' => $result['data'],

        ];
    }

    public function sendVideo(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $videoUrl,
    ): array {

        $result = $this->post(

            $accessToken,

            "/{$phoneNumberId}/messages",

            [

                'messaging_product' => 'whatsapp',

                'to' => $to,

                'type' => 'video',

                'video' => [

                    'link' => $videoUrl,

                ],

            ]

        );

        if (! $result['success']) {
            return $result;
        }

        return [

            'success' => true,

            'message_id' =>
            $result['data']['messages'][0]['id'] ?? null,

            'raw' => $result['data'],

        ];
    }

    public function sendAudio(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $audioUrl,
    ): array {

        $result = $this->post(

            $accessToken,

            "/{$phoneNumberId}/messages",

            [

                'messaging_product' => 'whatsapp',

                'to' => $to,

                'type' => 'audio',

                'audio' => [

                    'link' => $audioUrl,

                ],

            ]

        );

        if (! $result['success']) {
            return $result;
        }

        return [

            'success' => true,

            'message_id' =>
            $result['data']['messages'][0]['id'] ?? null,

            'raw' => $result['data'],

        ];
    }

    /**
     * Upload media.
     */
    public function uploadMedia(
        string $phoneNumberId,
        string $accessToken,
        string $path,
        string $mimeType,
    ): array {

        $response = Http::withToken($accessToken)

            ->attach(

                'file',

                fopen($path, 'r'),

                basename($path)

            )

            ->post(

                "{$this->baseUrl}/{$phoneNumberId}/media",

                [

                    'messaging_product' => 'whatsapp',

                    'type' => $mimeType,

                ]

            );

        return $this->handleResponse($response);
    }
}
