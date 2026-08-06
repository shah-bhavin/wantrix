<?php

namespace App\Services\Whatsapp\Providers;

use App\Enums\MessageType;
use App\Models\MediaLibrary;
use App\Models\Message;
use App\Models\WhatsappAccount;
use App\Services\Meta\MetaApiService;
use App\Services\Whatsapp\Contracts\WhatsappProvider;
use App\Services\Whatsapp\TemplateVariableService;

class MetaProvider implements WhatsappProvider
{
    public function __construct(
        protected MetaApiService $meta,
        protected TemplateVariableService $variables,
    ) {}

    public function send(Message $message): array
    {
        $message->loadMissing([

            'campaign.whatsappAccount',

            'contact',

            'whatsappTemplate',

            'media',

        ]);

        $account = $message->campaign->whatsappAccount;

        if (!$account) {

            return [

                'success' => false,

                'error' => 'No WhatsApp account selected.',

            ];
        }

        if (!$account->access_token) {

            return [

                'success' => false,

                'error' => 'WhatsApp account has no access token.',

            ];
        }

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

                    'error' => "Media '{$message->media->original_name}' has not been uploaded to Meta."

                ];
            }
        }

        return match ($message->type) {

            MessageType::TEXT =>

            $this->meta->sendText(

                phoneNumberId: $account->phone_number_id,

                //accessToken: decrypt($account->access_token),
                accessToken: $account->access_token,
                to: $message->contact->phone_number,

                body: $message->body,

            ),

            MessageType::TEMPLATE => (function () use ($message, $account) {

                $variables = $this->variables->resolve($message);

                return $this->meta->sendTemplate(

                    phoneNumberId: $account->phone_number_id,

                    accessToken: $account->access_token,

                    to: $message->contact->phone_number,

                    templateName: $message->whatsappTemplate->name,

                    language: $message->whatsappTemplate->language,

                    variables: $variables,

                );
            })(),

            MessageType::BUTTONS =>

            $this->meta->sendInteractiveButtons(

                phoneNumberId: $account->phone_number_id,

                accessToken: $account->access_token,

                to: $message->contact->phone_number,

                body: $message->body,

                buttons: $message->template_variables ?? [],

            ),

            MessageType::IMAGE =>

            $this->meta->sendImage(

                phoneNumberId: $account->phone_number_id,

                accessToken: $account->access_token,

                to: $message->contact->phone_number,

                metaMediaId: $message->media->meta_media_id

            ),

            MessageType::DOCUMENT =>

            $this->meta->sendDocument(

                phoneNumberId: $account->phone_number_id,

                accessToken: $account->access_token,

                to: $message->contact->phone_number,

                metaMediaId: $message->media->meta_media_id,

            ),

            MessageType::VIDEO =>

            $this->meta->sendVideo(

                phoneNumberId: $account->phone_number_id,

                accessToken: $account->access_token,

                to: $message->contact->phone_number,

                metaMediaId: $message->media->meta_media_id,

            ),

            MessageType::AUDIO =>

            $this->meta->sendAudio(

                phoneNumberId: $account->phone_number_id,

                accessToken: $account->access_token,

                to: $message->contact->phone_number,

                metaMediaId: $message->media->meta_media_id,

            ),

            default => [

                'success' => false,

                'error' => 'Unsupported message type.',

            ],
        };
    }

    public function uploadMedia(
        WhatsappAccount $account,
        MediaLibrary $media
        ): array {

            /*
            |--------------------------------------------------------------------------
            | Temporary Stub
            |--------------------------------------------------------------------------
            |
            | The actual implementation will upload the file to the Meta Graph API.
            | Until then we return a clean error instead of pretending success.
            |
            */

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

    public function uploadMedia()
    {
        // Fake upload
    }
}
