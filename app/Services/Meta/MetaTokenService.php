<?php

namespace App\Services\Meta;

use App\Models\WhatsappAccount;

class MetaTokenService
{
    public function __construct(
        protected MetaApiService $meta
    ) {
    }

    /**
     * Get the decrypted access token for a WhatsApp account.
     */
    public function getAccessToken(WhatsappAccount $account): string
    {
        return $account->access_token;
    }

    /**
     * Encrypt and store a WhatsApp access token.
     */
    public function storeAccessToken(
        WhatsappAccount $account,
        string $token
    ): void {
        $account->update([
            'access_token' => $token,
        ]);
    }

    /**
     * Debug an access token using Meta's Graph API.
     */
    public function debugToken(string $token): array
    {
        $appId = config('meta.app_id');
        $appSecret = config('meta.app_secret');

        if (!$appId || !$appSecret) {
            return [
                'success' => false,
                'error' => 'Meta App ID or App Secret is not configured.',
            ];
        }

        $appAccessToken = $appId . '|' . $appSecret;

        return $this->meta->debugToken(
            $token,
            $appAccessToken
        );
    }
}