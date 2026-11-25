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
use Illuminate\Support\Facades\Storage;
use Throwable;

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
            'conversation',
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

        if ((int) $message->vendor_id !== (int) $account->vendor_id) {
            return [
                'success' => false,
                'error' => 'WhatsApp account does not belong to this message vendor.',
            ];
        }

        if (! $account->is_active) {
            return [
                'success' => false,
                'error' => 'WhatsApp account is inactive.',
            ];
        }


        if (! $account->access_token || blank($account->phone_number_id)) {
            return [
                'success' => false,
                'error' => 'WhatsApp account credentials are incomplete.',
            ];
        }

        try {
            $accessToken = $this->tokens->getAccessToken($account);
        } catch (Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'error' => 'Unable to retrieve WhatsApp access token.',
            ];
        }

        $recipient = $message->getRecipientPhone();

        if (! $recipient) {
            return [
                'success' => false,
                'error' => 'Message recipient phone number is missing.',
            ];
        }

        if (! $this->isWithinCustomerServiceWindow($message)) {
            return [
                'success' => false,
                'error' => 'The 24-hour customer service window has expired. Use an approved WhatsApp template.',
            ];
        }

        if (in_array($message->type, [
            MessageType::IMAGE,
            MessageType::DOCUMENT,
            MessageType::VIDEO,
            MessageType::AUDIO,
        ], true)) {
            if (
                $message->media
                && (int) $message->media->vendor_id !== (int) $message->vendor_id
            ) {
                return [
                    'success' => false,
                    'error' => 'Message media does not belong to this vendor.',
                ];
            }

            if (
                ! $message->media
                || ! $message->media->uploaded_to_meta
                || blank($message->media->meta_media_id)
            ) {
                return [
                    'success' => false,
                    'error' => 'Message media has not been uploaded to Meta.',
                ];
            }
        }

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

        if (
            $message->whatsappTemplate->vendor_id !== $account->vendor_id
            || $message->whatsappTemplate->whatsapp_account_id !== $account->id
        ) {
            return [
                'success' => false,
                'error' => 'WhatsApp template does not belong to this account.',
            ];
        }

        if (! $message->whatsappTemplate->isApproved()) {
            return [
                'success' => false,
                'error' => 'Only approved WhatsApp templates can be sent.',
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
        if ($account->vendor_id !== $media->vendor_id) {
            return [
                'success' => false,
                'error' => 'Media does not belong to this WhatsApp account owner.',
            ];
        }

        if (filled($media->meta_media_id) && $media->uploaded_to_meta) {
            return [
                'success' => true,
                'media_id' => $media->meta_media_id,
            ];
        }

        if (
            blank($account->phone_number_id)
            || blank($account->access_token)
        ) {
            return [
                'success' => false,
                'error' => 'WhatsApp account credentials are incomplete.',
            ];
        }

        if (
            ! in_array($media->type?->value, [
                'image',
                'video',
                'document',
                'audio',
            ], true)
        ) {
            return [
                'success' => false,
                'error' => 'Unsupported media type.',
            ];
        }

        try {
            $disk = Storage::disk($media->disk);

            if (! $disk->exists($media->path)) {
                return [
                    'success' => false,
                    'error' => 'Media file was not found in storage.',
                ];
            }

            $stream = $disk->readStream($media->path);

            if (! is_resource($stream)) {
                return [
                    'success' => false,
                    'error' => 'Unable to read media from storage.',
                ];
            }

            try {
                $accessToken = $this->tokens->getAccessToken($account);

                $result = $this->meta->uploadMediaStream(
                    phoneNumberId: $account->phone_number_id,
                    accessToken: $accessToken,
                    stream: $stream,
                    filename: $media->original_name ?: basename($media->path),
                    mimeType: $media->mime_type,
                );
            } finally {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }

            $metaMediaId = data_get($result, 'data.id');

            if (
                ! ($result['success'] ?? false)
                || blank($metaMediaId)
            ) {
                return [
                    'success' => false,
                    'error' => data_get(
                        $result,
                        'error.error.message',
                        $result['error'] ?? 'Meta media upload failed.'
                    ),
                ];
            }

            $media->update([
                'meta_media_id' => $metaMediaId,
                'uploaded_to_meta' => true,
                'uploaded_at' => now(),
            ]);

            return [
                'success' => true,
                'media_id' => $metaMediaId,
            ];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'error' => 'Media upload failed: ' . $exception->getMessage(),
            ];
        }
    }

    public function verifyConnection(array $credentials): array
    {
        $phoneNumberId = $credentials['phone_number_id'] ?? null;
        $accessToken = $credentials['access_token'] ?? null;

        if (blank($phoneNumberId) || blank($accessToken)) {
            return [
                'success' => false,
                'error' => 'Phone Number ID and access token are required.',
            ];
        }

        return $this->meta->verifyConnection(
            (string) $phoneNumberId,
            (string) $accessToken
        );
    }

    public function subscribeWebhook(array $credentials): array
    {
        $wabaId = $credentials['waba_id'] ?? null;
        $accessToken = $credentials['access_token'] ?? null;

        if (blank($wabaId) || blank($accessToken)) {
            return [
                'success' => false,
                'error' => 'WABA ID and access token are required.',
            ];
        }

        return $this->meta->subscribeWebhook(
            (string) $wabaId,
            (string) $accessToken
        );
    }

    protected function isWithinCustomerServiceWindow(
        Message $message
    ): bool {
        // Approved templates can be used outside the 24-hour window.
        if ($message->type === MessageType::TEMPLATE) {
            return true;
        }

        $lastIncomingAt = $message->conversation?->last_incoming_at;

        return $lastIncomingAt !== null
            && $lastIncomingAt->greaterThan(now()->subHours(24));
    }
}
