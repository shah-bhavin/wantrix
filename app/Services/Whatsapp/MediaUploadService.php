<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappAccount;
use App\Services\Meta\MetaApiService;

class MediaUploadService
{
    public function __construct(
        protected MetaApiService $meta,
    ) {}

    public function upload(

        WhatsappAccount $account,

        string $path,

        string $mimeType,

    ): array {

        return $this->meta->uploadMedia(

            phoneNumberId: $account->phone_number_id,

            accessToken: $account->access_token,

            path: $path,

            mimeType: $mimeType,

        );
    }
}