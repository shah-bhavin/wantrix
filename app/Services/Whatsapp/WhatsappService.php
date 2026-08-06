<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappAccount;
use App\Services\Meta\MetaApiService;

class WhatsappService
{
    public function __construct(
        protected MetaApiService $meta,
    ) {}

    public function verify(WhatsappAccount $account): array
    {
        return $this->meta->verifyConnection(
            $account->phone_number_id,
            $account->access_token
        );
    }
}