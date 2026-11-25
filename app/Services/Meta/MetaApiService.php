<?php

namespace App\Services\Meta;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            ->connectTimeout(10)
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
        $data = $response->json() ?? [];

        if ($response->failed()) {
            Log::error('META GRAPH API ERROR', [
                'status' => $response->status(),
                'error_code' => data_get($data, 'error.code'),
                'error_subcode' => data_get($data, 'error.error_subcode'),
                'error_type' => data_get($data, 'error.type'),
                'fbtrace_id' => data_get($data, 'error.fbtrace_id'),
            ]);

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $data,
            ];
        }

        Log::info('META GRAPH API SUCCESS', [
            'status' => $response->status(),
        ]);

        return [
            'success' => true,
            'status' => $response->status(),
            'data' => $data,
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

    public function getPhoneNumbers(
        string $wabaId,
        string $accessToken
    ): array {

        return $this->get(
            $accessToken,
            "/{$wabaId}/phone_numbers",
            [
                'fields' => implode(',', [
                    'id',
                    'verified_name',
                    'display_phone_number',
                    'quality_rating',
                    'code_verification_status',
                ]),
            ]
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
        $allTemplates = [];
        $after = null;
        $seenCursors = [];
        $lastResult = null;

        for ($page = 0; $page < 100; $page++) {
            $query = ['limit' => 100];

            if ($after !== null) {
                $query['after'] = $after;
            }

            $result = $this->get(
                $accessToken,
                "/{$wabaId}/message_templates",
                $query
            );

            if (! ($result['success'] ?? false)) {
                return $result;
            }

            $lastResult = $result;

            $pageTemplates = data_get(
                $result,
                'data.data',
                []
            );

            $allTemplates = array_merge(
                $allTemplates,
                $pageTemplates
            );

            $nextCursor = data_get(
                $result,
                'data.paging.cursors.after'
            );

            if (blank($nextCursor) || isset($seenCursors[$nextCursor])) {
                break;
            }

            $seenCursors[$nextCursor] = true;
            $after = $nextCursor;

            if ($page === 99) {
                return [
                    'success' => false,
                    'error' => 'Template pagination safety limit reached.',
                ];
            }
        }

        return [
            'success' => true,
            'status' => $lastResult['status'] ?? 200,
            'data' => [
                'data' => $allTemplates,
                'paging' => $lastResult['data']['paging'] ?? [],
            ],
        ];
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
     * Register a WhatsApp phone number for Cloud API messaging.
     *
     * This registers the phone number and sets/enables
     * the 6-digit two-step verification PIN.
     */
    public function registerPhone(
        string $phoneNumberId,
        string $accessToken,
        string $pin
    ): array {

        return $this->post(
            $accessToken,
            "/{$phoneNumberId}/register",
            [
                'messaging_product' => 'whatsapp',
                'pin' => $pin,
            ]
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

    // public function sendText(
    //     string $phoneNumberId,
    //     string $accessToken,
    //     string $to,
    //     string $body,
    // ): array {

    //     $result = $this->post(

    //         $accessToken,

    //         "/{$phoneNumberId}/messages",

    //         [

    //             'messaging_product' => 'whatsapp',

    //             'recipient_type' => 'individual',

    //             'to' => $to,

    //             'type' => 'text',

    //             'text' => [

    //                 'preview_url' => false,

    //                 'body' => $body,

    //             ],

    //         ]

    //     );

    //     if (! $result['success']) {

    //         return $result;
    //     }

    //     return [

    //         'success' => true,

    //         'message_id' =>

    //         $result['data']['messages'][0]['id'] ?? null,

    //         'raw' => $result['data'],

    //     ];
    // }

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

        return $this->formatMessageResponse($result);
    }

    /**
     * Normalize a successful WhatsApp message API response.
     *
     * All message sending methods should return the same structure
     * so callers can reliably access message_id.
     */
    protected function formatMessageResponse(array $result): array
    {
        if (! ($result['success'] ?? false)) {
            return $result;
        }

        $messageId = data_get($result, 'data.messages.0.id');

        if (blank($messageId)) {
            Log::error('META RESPONSE MISSING MESSAGE ID', [
                'status' => $result['status'] ?? null,
            ]);

            return [
                'success' => false,
                'error' => 'Meta did not return a message ID.',
                'raw' => $result['data'] ?? null,
            ];
        }

        return [
            'success' => true,
            'message_id' => $messageId,
            'raw' => $result['data'] ?? null,
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

        return $this->formatMessageResponse($result);
    }

    public function sendInteractiveButtons(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $body,
        array $buttons,
    ): array {

        $result = $this->post(
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
                            })
                            ->all(),
                    ],
                ],
            ]
        );

        return $this->formatMessageResponse($result);
    }

    public function sendImage(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $metaMediaId,
    ): array {

        $result = $this->post(
            $accessToken,
            "/{$phoneNumberId}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'image',
                'image' => [
                    'id' => $metaMediaId,
                ],
            ]
        );

        return $this->formatMessageResponse($result);
    }

    public function sendDocument(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $metaMediaId,
    ): array {

        $result = $this->post(
            $accessToken,
            "/{$phoneNumberId}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'document',
                'document' => [
                    'id' => $metaMediaId,
                ],
            ]
        );

        return $this->formatMessageResponse($result);
    }

    public function sendVideo(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $metaMediaId,
    ): array {

        $result = $this->post(
            $accessToken,
            "/{$phoneNumberId}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'video',
                'video' => [
                    'id' => $metaMediaId,
                ],
            ]
        );

        return $this->formatMessageResponse($result);
    }

    public function sendAudio(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $metaMediaId,
    ): array {

        $result = $this->post(
            $accessToken,
            "/{$phoneNumberId}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'audio',
                'audio' => [
                    'id' => $metaMediaId,
                ],
            ]
        );

        return $this->formatMessageResponse($result);
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
        if (! is_file($path) || ! is_readable($path)) {
            return [
                'success' => false,
                'error' => 'Media file is missing or unreadable.',
            ];
        }

        $stream = fopen($path, 'r');

        if ($stream === false) {
            return [
                'success' => false,
                'error' => 'Unable to open media file.',
            ];
        }

        try {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->connectTimeout(15)
                ->timeout(120)
                ->attach(
                    'file',
                    $stream,
                    basename($path),
                    ['Content-Type' => $mimeType]
                )
                ->post(
                    "{$this->baseUrl}/{$phoneNumberId}/media",
                    [
                        'messaging_product' => 'whatsapp',
                        'type' => $mimeType,
                    ]
                );

            return $this->handleResponse($response);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }



    public function uploadMediaStream(
        string $phoneNumberId,
        string $accessToken,
        $stream,
        string $filename,
        string $mimeType,
    ): array {
        if (! is_resource($stream)) {
            return [
                'success' => false,
                'error' => 'Invalid media stream.',
            ];
        }

        try {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->connectTimeout(15)
                ->timeout(120)
                ->attach(
                    'file',
                    $stream,
                    basename($filename),
                    ['Content-Type' => $mimeType]
                )
                ->post(
                    "{$this->baseUrl}/{$phoneNumberId}/media",
                    [
                        'messaging_product' => 'whatsapp',
                        'type' => $mimeType,
                    ]
                );

            return $this->handleResponse($response);
        } catch (\Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'error' => 'Meta media upload request failed.',
            ];
        }
    }


    public function getWabaDetails(
        string $wabaId,
        string $accessToken
    ): array {

        return $this->get(
            $accessToken,
            "/{$wabaId}",
            [
                'fields' => implode(',', [
                    'id',
                    'name',
                    'currency',
                    'timezone_id',
                    'message_template_namespace',
                ]),
            ]
        );
    }

    /**
     * Debug an access token using Meta's Graph API.
     */
    public function debugToken(
        string $inputToken,
        string $appAccessToken
    ): array {
        return $this->get(
            $appAccessToken,
            '/debug_token',
            [
                'input_token' => $inputToken,
            ]
        );
    }
}
