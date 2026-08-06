<?php

namespace App\Services\Whatsapp\Providers;

use App\Enums\MessageType;
use App\Models\Message;
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

        $message->loadMissing(
            'whatsappTemplate'
        );

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

                imageUrl: $message->body,

            ),

            MessageType::DOCUMENT =>

            $this->meta->sendDocument(

                phoneNumberId: $account->phone_number_id,

                accessToken: $account->access_token,

                to: $message->contact->phone_number,

                documentUrl: $message->body,

                filename: 'document.pdf',

            ),

            MessageType::VIDEO =>

            $this->meta->sendVideo(

                phoneNumberId: $account->phone_number_id,

                accessToken: $account->access_token,

                to: $message->contact->phone_number,

                videoUrl: $message->body,

            ),

            MessageType::AUDIO =>

            $this->meta->sendAudio(

                phoneNumberId: $account->phone_number_id,

                accessToken: $account->access_token,

                to: $message->contact->phone_number,

                audioUrl: $message->body,

            ),

            default => [

                'success' => false,

                'error' => 'Unsupported message type.',

            ],
        };
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
