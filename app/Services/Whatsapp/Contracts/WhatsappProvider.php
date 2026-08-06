<?php

namespace App\Services\Whatsapp\Contracts;

use App\Models\Message;

interface WhatsappProvider
{
    public function send(Message $message): array;

    public function verifyConnection(array $credentials): array;

    public function subscribeWebhook(array $credentials): array;
}