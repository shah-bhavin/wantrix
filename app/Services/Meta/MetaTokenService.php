<?php

namespace App\Services\Meta;

use App\Models\WhatsappAccount;
use RuntimeException;

class MetaTokenService
{
    public function __construct(
        protected MetaApiService $meta
    ) {}

    public function getAccessToken(WhatsappAccount $account): string
    {
        $account->refresh();

        $token = $account->access_token;

        if (! is_string($token) || trim($token) === '') {
            throw new RuntimeException(
                'WhatsApp access token is missing. Reconnect the WhatsApp account.'
            );
        }

        return trim($token);
    }

    public function storeAccessToken(
        WhatsappAccount $account,
        string $token
    ): void {
        if (trim($token) === '') {
            throw new RuntimeException('Cannot store an empty Meta access token.');
        }

        $account->access_token = trim($token);
        $account->save();
    }

    public function debugToken(string $token): array
    {
        $appId = config('meta.app_id');
        $appSecret = config('meta.app_secret');

        if (blank($appId) || blank($appSecret) || blank($token)) {
            return [
                'success' => false,
                'error' => 'Meta App credentials or access token are missing.',
            ];
        }

        return $this->meta->debugToken(
            $token,
            $appId . '|' . $appSecret
        );
    }
}
