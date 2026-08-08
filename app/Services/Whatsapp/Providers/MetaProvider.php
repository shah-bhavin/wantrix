<?php

namespace App\Services\Whatsapp\Providers;

use App\Enums\MessageType;
use App\Models\MediaLibrary;
use App\Models\Message;
use App\Models\WhatsappAccount;
use App\Services\Meta\MetaApiService;
use App\Services\Meta\MetaTokenService;
use App\Services\Whatsapp\Contracts\WhatsappProvider;
use App\Services\Whatsapp\TemplateVariableService;

class MetaProvider implements WhatsappProvider
{
    public function __construct(
        protected MetaApiService $meta,
        protected MetaTokenService $tokens,
        protected TemplateVariableService $variables,
    ) {}

    public function send(Message $message): array
    {
        $message->loadMissing([
            'campaign.whatsappAccount',
            'contact',
            'whatsappTemplate',
            'media',
            'whatsappAccount',
        ]);

        $account = $message->whatsappAccount;

        if (! $account && $message->campaign) {
            $account = $message->campaign->whatsappAccount;
        }

        if (! $account) {
            return [
                'success' => false,
                'error' => 'No WhatsApp account associated with this message.',
            ];
        }

        if (! $account->access_token) {
            return [
                'success' => false,
                'error' => 'WhatsApp account has no access token.',
            ];
        }

        try {
            $accessToken = $this->tokens->getAccessToken($account);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => 'Unable to decrypt WhatsApp access token.',
            ];
        }

        try {
            $accessToken = $this->tokens->getAccessToken($account);
        } catch (\Throwable $e) {
            report($e);

            return [
                'success' => false,
                'error' => 'Unable to decrypt WhatsApp access token.',
            ];
        }

        $recipient = $message->getRecipientPhone();

        if (! $recipient) {
            return [
                'success' => false,
                'error' => 'Message recipient phone number is missing.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Media Validation
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $message->type,
                [
                    MessageType::IMAGE,
                    MessageType::DOCUMENT,
                    MessageType::VIDEO,
                    MessageType::AUDIO,
                ]
            )
        ) {
            if (
                ! $message->media
                || ! $message->media->uploaded_to_meta
                || blank($message->media->meta_media_id)
            ) {
                return [
                    'success' => false,
                    'error' => "Media '{$message->media?->original_name}' has not been uploaded to Meta.",
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Send Message
        |--------------------------------------------------------------------------
        */

        return match ($message->type) {

            MessageType::TEXT => $this->meta->sendText(
                phoneNumberId: $account->phone_number_id,
                accessToken: $accessToken,
                to: $recipient,
                body: $message->body,
            ),

            MessageType::TEMPLATE => $this->sendTemplate(
                $message,
                $account,
                $accessToken,
                $recipient,
            ),

            MessageType::BUTTONS => $this->meta->sendInteractiveButtons(
                phoneNumberId: $account->phone_number_id,
                accessToken: $accessToken,
                to: $recipient,
                body: $message->body,
                buttons: $message->template_variables ?? [],
            ),

            MessageType::IMAGE => $this->meta->sendImage(
                phoneNumberId: $account->phone_number_id,
                accessToken: $accessToken,
                to: $recipient,
                metaMediaId: $message->media->meta_media_id,
            ),

            MessageType::DOCUMENT => $this->meta->sendDocument(
                phoneNumberId: $account->phone_number_id,
                accessToken: $accessToken,
                to: $recipient,
                metaMediaId: $message->media->meta_media_id,
            ),

            MessageType::VIDEO => $this->meta->sendVideo(
                phoneNumberId: $account->phone_number_id,
                accessToken: $accessToken,
                to: $recipient,
                metaMediaId: $message->media->meta_media_id,
            ),

            MessageType::AUDIO => $this->meta->sendAudio(
                phoneNumberId: $account->phone_number_id,
                accessToken: $accessToken,
                to: $recipient,
                metaMediaId: $message->media->meta_media_id,
            ),

            default => [
                'success' => false,
                'error' => 'Unsupported message type.',
            ],
        };
    }

    protected function sendTemplate(
        Message $message,
        WhatsappAccount $account,
        string $accessToken,
        string $recipient,
    ): array {
        if (! $message->whatsappTemplate) {
            return [
                'success' => false,
                'error' => 'WhatsApp template is missing.',
            ];
        }

        $variables = $this->variables->resolve($message);

        return $this->meta->sendTemplate(
            phoneNumberId: $account->phone_number_id,
            accessToken: $accessToken,
            to: $recipient,
            templateName: $message->whatsappTemplate->name,
            language: $message->whatsappTemplate->language,
            variables: $variables,
        );
    }

    public function uploadMedia(
        WhatsappAccount $account,
        MediaLibrary $media
    ): array {
        return [
            'success' => false,
            'error' => 'Meta media upload is not implemented yet.',
        ];
    }

    public function verifyConnection(array $credentials): array
    {
        return [];
    }

    public function subscribeWebhook(array $credentials): array
    {
        return [];
    }
}
