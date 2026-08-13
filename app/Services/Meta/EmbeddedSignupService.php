<?php

namespace App\Services\Meta;

use App\Data\Meta\EmbeddedSignupResult;
use App\Enums\WhatsappAccountStatus;
use App\Models\Vendor;
use App\Models\WhatsappAccount;
use Illuminate\Support\Facades\Log;

class EmbeddedSignupService
{
    public function __construct(protected MetaApiService $meta, protected MetaDiscoveryService $discovery, protected MetaTokenService $tokens) {}

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
    public function finish(
        Vendor $vendor,
        string $authorizationCode,
        string $wabaId,
        string $phoneNumberId,
        ?string $businessId = null
    ): EmbeddedSignupResult {

        /*
    |--------------------------------------------------------------------------
    | Step 1: Exchange authorization code
    |--------------------------------------------------------------------------
    */

        $exchange = $this->exchangeCode($authorizationCode);

        if (! ($exchange['success'] ?? false)) {

            return EmbeddedSignupResult::failure(
                'Unable to exchange authorization code.',
                $exchange['error'] ?? []
            );
        }

        $token = $exchange['data']['access_token'] ?? null;

        if (blank($token)) {

            return EmbeddedSignupResult::failure(
                'Meta did not return an access token.'
            );
        }

        Log::info('META EMBEDDED TOKEN RECEIVED', [
            'received' => true,
            'length' => strlen($token),
            'waba_id' => $wabaId,
            'phone_number_id' => $phoneNumberId,
            'business_id' => $businessId,
        ]);


        /*
    |--------------------------------------------------------------------------
    | Step 2: Get WABA details
    |--------------------------------------------------------------------------
    */

        $waba = $this->meta->getWabaDetails(
            $wabaId,
            $token
        );

        Log::info('META WABA DETAILS RESULT', [
            'success' => $waba['success'] ?? false,
            'status' => $waba['status'] ?? null,
            'error' => $waba['error'] ?? null,
        ]);

        if (! ($waba['success'] ?? false)) {

            return EmbeddedSignupResult::failure(
                'Unable to retrieve WhatsApp Business Account details.',
                $waba['error'] ?? []
            );
        }

        $wabaData = $waba['data'] ?? [];


        /*
    |--------------------------------------------------------------------------
    | Step 3: Get phone numbers through WABA
    |--------------------------------------------------------------------------
    */

        $phoneNumbers = $this->meta->getPhoneNumbers(
            $wabaId,
            $token
        );

        \Log::info('META WABA PHONE NUMBERS RESULT', [
            'success' => $phoneNumbers['success'] ?? false,
            'status' => $phoneNumbers['status'] ?? null,
            'error' => $phoneNumbers['error'] ?? null,
            'phone_count' => count(
                $phoneNumbers['data']['data'] ?? []
            ),
        ]);

        if (! ($phoneNumbers['success'] ?? false)) {

            return EmbeddedSignupResult::failure(
                'Unable to retrieve WhatsApp phone numbers.',
                $phoneNumbers['error'] ?? []
            );
        }

        $phoneList = $phoneNumbers['data']['data'] ?? [];


        /*
    |--------------------------------------------------------------------------
    | Step 4: Find the phone number returned by Embedded Signup
    |--------------------------------------------------------------------------
    */

        $phoneData = collect($phoneList)->firstWhere(
            'id',
            $phoneNumberId
        );


        /*
    |--------------------------------------------------------------------------
    | Fallback: if Meta returned only one phone number
    |--------------------------------------------------------------------------
    */

        if (! $phoneData && count($phoneList) === 1) {

            $phoneData = $phoneList[0];

            \Log::warning(
                'META PHONE ID NOT FOUND IN WABA LIST. USING ONLY PHONE.',
                [
                    'requested_phone_number_id' => $phoneNumberId,
                    'returned_phone_number_id' => $phoneData['id'] ?? null,
                ]
            );

            $phoneNumberId = $phoneData['id'] ?? $phoneNumberId;
        }


        /*
    |--------------------------------------------------------------------------
    | No phone found
    |--------------------------------------------------------------------------
    */

        if (! $phoneData) {

            Log::error(
                'META PHONE NUMBER NOT FOUND IN WABA.',
                [
                    'requested_phone_number_id' => $phoneNumberId,
                    'waba_id' => $wabaId,
                    'available_phone_numbers' => collect($phoneList)
                        ->pluck('id')
                        ->values()
                        ->all(),
                ]
            );

            return EmbeddedSignupResult::failure(
                'The phone number returned by Meta was not found in the WABA.'
            );
        }


        Log::info(
            'META PHONE NUMBER FOUND',
            [
                'phone_number_id' => $phoneData['id'] ?? null,
                'display_phone_number' =>
                $phoneData['display_phone_number'] ?? null,
                'verified_name' =>
                $phoneData['verified_name'] ?? null,
                'quality_rating' =>
                $phoneData['quality_rating'] ?? null,
            ]
        );


        /*
    |--------------------------------------------------------------------------
    | Step 5: Save WhatsApp account
    |--------------------------------------------------------------------------
    */

        $account = $this->createWhatsappAccount(
            $vendor,
            [
                'business_id' => $businessId,

                'waba_id' => $wabaId,

                'phone_number_id' =>
                $phoneData['id'] ?? $phoneNumberId,

                'details' => $phoneData,

                'waba' => $wabaData,
            ],
            $token
        );


        /*
    |--------------------------------------------------------------------------
    | Step 6: Subscribe WABA to webhook
    |--------------------------------------------------------------------------
    */

        $subscription = $this->meta->subscribeWebhook(
            $wabaId,
            $token
        );

        Log::info(
            'META WABA WEBHOOK SUBSCRIPTION RESULT',
            [
                'success' => $subscription['success'] ?? false,
                'status' => $subscription['status'] ?? null,
                'error' => $subscription['error'] ?? null,
            ]
        );


        if (! ($subscription['success'] ?? false)) {

            /*
         * Account has already been saved.
         *
         * Do NOT report the entire onboarding as failed.
         */

            $account->update([
                'last_error' =>
                'WhatsApp account connected, but webhook subscription failed.'
            ]);

            return EmbeddedSignupResult::success(

                accessToken: $token,

                businessId: $businessId,

                wabaId: $wabaId,

                phoneNumberId: $phoneData['id'] ?? $phoneNumberId,

                businessName: $wabaData['name'] ?? null,

                phoneNumber: $phoneData['display_phone_number'] ?? null,

                verifiedName: $phoneData['verified_name'] ?? null,

                raw: [
                    'waba' => $wabaData,
                    'phone' => $phoneData,
                    'business_id' => $businessId,
                    'webhook_subscription' => $subscription,
                ]
            );
        }


        /*
    |--------------------------------------------------------------------------
    | Step 7: Success
    |--------------------------------------------------------------------------
    */

        return EmbeddedSignupResult::success(

            accessToken: $token,

            businessId: $businessId,

            wabaId: $wabaId,

            phoneNumberId: $phoneData['id'] ?? $phoneNumberId,

            businessName: $wabaData['name'] ?? null,

            phoneNumber: $phoneData['display_phone_number'] ?? null,

            verifiedName: $phoneData['verified_name'] ?? null,

            raw: [
                'waba' => $wabaData,
                'phone' => $phoneData,
                'business_id' => $businessId,
            ]
        );
    }

    private function createWhatsappAccount(
        Vendor $vendor,
        array $account,
        string $token
    ): WhatsappAccount {

        $details = $account['details'] ?? [];

        return $vendor
            ->whatsappAccounts()
            ->updateOrCreate(

                [
                    'phone_number_id' =>
                    $account['phone_number_id'],
                ],

                [

                    'name' =>
                    $details['verified_name']
                        ?? $details['display_phone_number']
                        ?? 'WhatsApp',

                    'provider' => 'meta',

                    'phone_number' =>
                    $details['display_phone_number']
                        ?? null,

                    'display_phone_number' =>
                    $details['display_phone_number']
                        ?? null,

                    'verified_name' =>
                    $details['verified_name']
                        ?? null,

                    'quality_rating' =>
                    $details['quality_rating']
                        ?? null,

                    'verification_status' =>
                    $details['code_verification_status']
                        ?? null,

                    'waba_id' =>
                    $account['waba_id'],

                    'business_id' =>
                    $account['business_id'] ?? null,

                    'access_token' =>
                    $token,

                    'status' =>
                    WhatsappAccountStatus::CONNECTED,

                    'last_verified_at' =>
                    now(),

                    'last_error' =>
                    null,
                ]
            );
    }
}
