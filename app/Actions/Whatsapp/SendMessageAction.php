<?php

namespace App\Actions\Whatsapp;

use App\Models\Message;
use App\Services\Meta\MetaApiService;
use App\Services\Meta\MetaTokenService;
use App\Enums\MessageStatus;

class SendMessageAction
{
    public function __construct(
        protected MetaApiService $meta,
        protected MetaTokenService $tokens
    ) {}

    public function execute(
        Message $message
    ): array {

        if (! $message->whatsappAccount) {

            return [

                'success' => false,

                'error' => 'WhatsApp Account not found.'

            ];

        }

        $token = $this->tokens
            ->getAccessToken(
                $message->whatsappAccount
            );

        return $this->meta->sendText([
            'access_token' => $token,
            'message' => $message,
        ]);

    }
}