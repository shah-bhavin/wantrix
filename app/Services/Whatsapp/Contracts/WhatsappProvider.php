<?php

namespace App\Services\Whatsapp\Contracts;

use App\Models\Message;
use App\Models\MediaLibrary;
use App\Models\WhatsappAccount;

interface WhatsappProvider
{
    public function send(Message $message): array;

    public function uploadMedia(
        WhatsappAccount $account,
        MediaLibrary $media
    ): array;

    public function verifyConnection(array $credentials): array;

    public function subscribeWebhook(array $credentials): array;
}