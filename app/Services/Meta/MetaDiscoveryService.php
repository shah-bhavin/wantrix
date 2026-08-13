<?php

namespace App\Services\Meta;

class MetaDiscoveryService
{
    public function __construct(
        protected MetaApiService $meta
    ) {}

    /**
     * Discover WhatsApp accounts available through Embedded Signup.
     *
     * IMPORTANT:
     * The authorization token returned from Embedded Signup should
     * not be assumed to have access to /me/businesses.
     */
    public function discover(string $token): array
    {
        return [
            'success' => false,
            'error' => [
                'stage' => 'embedded_signup_discovery',
                'message' => 'WABA discovery must use the Embedded Signup session information instead of /me/businesses.',
            ],
        ];
    }
}


// namespace App\Services\Meta;

// class MetaDiscoveryService
// {
//     public function __construct(
//         protected MetaApiService $meta
//     ) {}

//     public function discover(string $token): array
//     {
//         \Illuminate\Support\Facades\Log::info(
//             'META DISCOVERY STARTED'
//         );

//         $result = [];

//         /*
//     |--------------------------------------------------------------------------
//     | Step 1: Get Business Manager businesses
//     |--------------------------------------------------------------------------
//     */

//         $businesses = $this->meta->getBusinesses($token);

//         \Illuminate\Support\Facades\Log::info(
//             'META DISCOVERY BUSINESSES RESULT',
//             [
//                 'success' => $businesses['success'] ?? false,
//                 'status' => $businesses['status'] ?? null,
//                 'error' => $businesses['error'] ?? null,
//             ]
//         );

//         if (! ($businesses['success'] ?? false)) {

//             return [
//                 'success' => false,
//                 'error' => [
//                     'stage' => 'get_businesses',
//                     'response' => $businesses,
//                 ],
//             ];
//         }

//         $businessList = $businesses['data']['data'] ?? [];

//         \Illuminate\Support\Facades\Log::info(
//             'META DISCOVERY BUSINESS COUNT',
//             [
//                 'count' => count($businessList),
//             ]
//         );

//         /*
//     |--------------------------------------------------------------------------
//     | Step 2: Find WABAs
//     |--------------------------------------------------------------------------
//     */

//         foreach ($businessList as $business) {

//             $businessId = $business['id'] ?? null;

//             if (! $businessId) {
//                 continue;
//             }

//             \Illuminate\Support\Facades\Log::info(
//                 'META DISCOVERY BUSINESS',
//                 [
//                     'business_id' => $businessId,
//                     'business_name' => $business['name'] ?? null,
//                 ]
//             );

//             $wabas = $this->meta->getOwnedWabas(
//                 $businessId,
//                 $token
//             );

//             \Illuminate\Support\Facades\Log::info(
//                 'META DISCOVERY WABA RESULT',
//                 [
//                     'business_id' => $businessId,
//                     'success' => $wabas['success'] ?? false,
//                     'status' => $wabas['status'] ?? null,
//                     'error' => $wabas['error'] ?? null,
//                 ]
//             );

//             if (! ($wabas['success'] ?? false)) {
//                 continue;
//             }

//             $wabaList = $wabas['data']['data'] ?? [];

//             /*
//         |--------------------------------------------------------------------------
//         | Step 3: Find phone numbers
//         |--------------------------------------------------------------------------
//         */

//             foreach ($wabaList as $waba) {

//                 $wabaId = $waba['id'] ?? null;

//                 if (! $wabaId) {
//                     continue;
//                 }

//                 \Illuminate\Support\Facades\Log::info(
//                     'META DISCOVERY WABA',
//                     [
//                         'business_id' => $businessId,
//                         'waba_id' => $wabaId,
//                         'waba_name' => $waba['name'] ?? null,
//                     ]
//                 );

//                 $phoneNumbers = $this->meta->getPhoneNumbers(
//                     $wabaId,
//                     $token
//                 );

//                 \Illuminate\Support\Facades\Log::info(
//                     'META DISCOVERY PHONE NUMBERS RESULT',
//                     [
//                         'waba_id' => $wabaId,
//                         'success' => $phoneNumbers['success'] ?? false,
//                         'status' => $phoneNumbers['status'] ?? null,
//                         'error' => $phoneNumbers['error'] ?? null,
//                     ]
//                 );

//                 if (! ($phoneNumbers['success'] ?? false)) {
//                     continue;
//                 }

//                 $phoneList = $phoneNumbers['data']['data'] ?? [];

//                 /*
//             |--------------------------------------------------------------------------
//             | Step 4: Get phone details
//             |--------------------------------------------------------------------------
//             */

//                 foreach ($phoneList as $phone) {

//                     $phoneNumberId = $phone['id'] ?? null;

//                     if (! $phoneNumberId) {
//                         continue;
//                     }

//                     $details = $this->meta->getPhoneDetails(
//                         $phoneNumberId,
//                         $token
//                     );

//                     \Illuminate\Support\Facades\Log::info(
//                         'META DISCOVERY PHONE DETAILS RESULT',
//                         [
//                             'phone_number_id' => $phoneNumberId,
//                             'success' => $details['success'] ?? false,
//                             'status' => $details['status'] ?? null,
//                             'error' => $details['error'] ?? null,
//                         ]
//                     );

//                     $result[] = [
//                         'business_id' => $businessId,

//                         'business_name' => $business['name'] ?? null,

//                         'waba_id' => $wabaId,

//                         'waba_name' => $waba['name'] ?? null,

//                         'phone_number_id' => $phoneNumberId,

//                         'details' => $details['data'] ?? [],
//                     ];
//                 }
//             }
//         }

//         \Illuminate\Support\Facades\Log::info(
//             'META DISCOVERY COMPLETED',
//             [
//                 'account_count' => count($result),
//             ]
//         );

//         return [
//             'success' => true,
//             'accounts' => $result,
//         ];
//     }
// }
?>