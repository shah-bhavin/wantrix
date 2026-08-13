<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappAccount;
use App\Services\Meta\MetaApiService;

class WhatsappService
{
    public function __construct(
        protected MetaApiService $meta,
    ) {}

    /**
     * Verify that the WhatsApp account connection is working.
     */
    public function verify(WhatsappAccount $account): array
    {
        return $this->meta->verifyConnection(
            $account->phone_number_id,
            $account->access_token
        );
    }

    public function register(
        WhatsappAccount $account,
        string $pin
    ): array {
        return $this->meta->registerPhone(
            $account->phone_number_id,
            $account->access_token,
            $pin
        );
    }

    /**
     * Send a plain text WhatsApp message.
     *
     * Important:
     * This method is only a wrapper around MetaApiService.
     * Business logic should stay here rather than inside Livewire.
     */
    public function sendText(
        WhatsappAccount $account,
        string $to,
        string $message,
    ): array {
        return $this->meta->sendText(
            $account->phone_number_id,
            $account->access_token,
            $to,
            $message
        );
    }

    /**
     * Send a WhatsApp template message.
     */
    public function sendTemplate(
        WhatsappAccount $account,
        string $to,
        string $templateName,
        string $language = 'en',
        array $variables = [],
    ): array {
        return $this->meta->sendTemplate(
            $account->phone_number_id,
            $account->access_token,
            $to,
            $templateName,
            $language,
            $variables
        );
    }
}
