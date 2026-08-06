<?php

namespace App\Services\Meta;

use App\Models\WhatsappAccount;

class MetaTokenService
{
    public function getAccessToken(WhatsappAccount $account): string {

        return decrypt(
            $account->access_token
        );
    }

    public function storeAccessToken(WhatsappAccount $account,string $token): void {

        $account->update([
            'access_token' => encrypt($token),
        ]);
    }

    public function debugToken(string $token): array
    {
        throw new \RuntimeException(
            'Not implemented yet.'
        );
    }
}