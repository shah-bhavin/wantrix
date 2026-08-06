<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappAccount;
use App\Services\Whatsapp\Contracts\WhatsappProvider;
use App\Services\Whatsapp\Providers\DummyProvider;
use App\Services\Whatsapp\Providers\MetaProvider;
use InvalidArgumentException;

class WhatsappManager
{
    public function __construct(protected DummyProvider $dummy, protected MetaProvider $meta,) {}

    public function provider(string $provider): WhatsappProvider
    {
        return match ($provider) {

            'dummy' => $this->dummy,

            'meta' => $this->meta,

            default => throw new InvalidArgumentException(
                "Unsupported WhatsApp provider: {$provider}"
            ),
        };
    }

    public function account(WhatsappAccount $account): WhatsappProvider {
        return $this->provider(
            $account->provider
        );
    }
}
