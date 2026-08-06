<?php

namespace App\Services\Meta;

use App\Data\Meta\EmbeddedSignupResult;
use App\Models\Vendor;
use App\Models\WhatsappAccount;
use App\Enums\WhatsappAccountStatus;

class EmbeddedSignupService
{
    public function __construct(protected MetaApiService $meta, protected MetaDiscoveryService $discovery) {}

    /**
     * Exchange authorization code for access token.
     */
    public function exchangeCode(string $code): array
    {
        return $this->meta->exchangeCode($code);
    }

    /**
     * Finish onboarding.
     */
    public function finish(Vendor $vendor, string $authorizationCode): EmbeddedSignupResult
    {

        $exchange = $this->exchangeCode(
            $authorizationCode
        );

        if (!$exchange['success']) {

            return EmbeddedSignupResult::failure(

                'Unable to exchange authorization code.',

                $exchange['error'] ?? []

            );
        }

        $token = $exchange['data']['access_token'];

        $accounts = $this->discovery
            ->discover($token);

        if (!$accounts['success']) {

            return EmbeddedSignupResult::failure(

                'Unable to discover WhatsApp account.',

                $accounts['error'] ?? []

            );
        }

        if (empty($accounts['accounts'])) {

            return EmbeddedSignupResult::failure(

                'No WhatsApp Business Account found.'

            );
        }

        $account = $accounts['accounts'][0];

        $this->createWhatsappAccount(
            $vendor,
            $account,
            $token
        );

        $subscription = $this->meta
            ->subscribeWebhook(
                $account['waba_id'],
                $token
            );

        if (! $subscription['success']) {

            return EmbeddedSignupResult::failure(

                'Unable to subscribe webhook.',

                $subscription['error'] ?? []

            );
        }

        return EmbeddedSignupResult::success(

            accessToken: $token,

            businessId: $account['business_id'],

            wabaId: $account['waba_id'],

            phoneNumberId: $account['phone_number_id'],

            businessName: $account['business_name'],

            phoneNumber: $account['details']['display_phone_number'] ?? null,

            verifiedName: $account['details']['verified_name'] ?? null,

            raw: $account

        );
    }

    private function createWhatsappAccount(Vendor $vendor, array $account, string $token): WhatsappAccount
    {

        return $vendor
            ->whatsappAccounts()
            ->updateOrCreate(

                [
                    'phone_number_id' => $account['phone_number_id'],
                ],

                [
                    'name' => $account['details']['verified_name']
                        ?? $account['details']['display_phone_number']
                        ?? 'WhatsApp',

                    'provider' => 'meta',

                    'phone_number' =>
                    $account['details']['display_phone_number']
                        ?? null,

                    'display_phone_number' =>
                    $account['details']['display_phone_number']
                        ?? null,

                    'verified_name' =>
                    $account['details']['verified_name']
                        ?? null,

                    'quality_rating' =>
                    $account['details']['quality_rating']
                        ?? null,

                    'verification_status' =>
                    $account['details']['code_verification_status']
                        ?? null,

                    'waba_id' => $account['waba_id'],

                    'access_token' => encrypt($token),

                    'status' => WhatsappAccountStatus::CONNECTED,

                    'last_verified_at' => now(),

                    'last_error' => null,
                ]
            );
    }
}
