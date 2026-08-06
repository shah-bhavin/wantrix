<?php

namespace App\Services\Meta;

use App\Services\Meta\MetaApiService;

class MetaDiscoveryService
{
    public function __construct(
        protected MetaApiService $meta
    ) {}

    public function discover(string $token): array
    {
        $result = [];

        $businesses = $this->meta->getBusinesses($token);

        if (! $businesses['success']) {

            return $businesses;
        }

        foreach ($businesses['data'] as $business) {

            $businessId = $business['id'];

            $wabas = $this->meta->getOwnedWabas(
                $businessId,
                $token
            );

            if (! $wabas['success']) {
                continue;
            }

            foreach ($wabas['data'] as $waba) {

                $phoneNumbers = $this->meta->getPhoneNumbers(
                    $waba['id'],
                    $token
                );

                if (! $phoneNumbers['success']) {
                    continue;
                }

                foreach ($phoneNumbers['data'] as $phone) {

                    $details = $this->meta->getPhoneDetails(
                        $phone['id'],
                        $token
                    );

                    $result[] = [

                        'business_id' => $businessId,

                        'business_name' => $business['name'] ?? null,

                        'waba_id' => $waba['id'],

                        'waba_name' => $waba['name'] ?? null,

                        'phone_number_id' => $phone['id'],

                        'details' => $details['data'] ?? [],
                    ];
                }
            }
        }

        return [
            'success' => true,
            'accounts' => $result,
        ];
    }
    
}
