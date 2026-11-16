<?php

namespace App\Actions\Contacts;

use App\Enums\ContactStatus;
use App\Models\Contact;
use App\Models\Vendor;
use App\Services\PhoneNumberService;

class CreateContactAction
{
    public function __construct(
        protected PhoneNumberService $phoneNumberService
    ) {}

    public function execute(
        Vendor $vendor,
        array $data
    ): Contact {
        return Contact::create([
            'vendor_id' => $vendor->id,

            'name' => $data['name'],

            'phone_number' => $this->phoneNumberService->normalize(
                $data['phone_number'] ?? null
            ),

            'email' => $data['email'] ?? null,
            'company' => $data['company'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'country' => $data['country'] ?? null,
            'notes' => $data['notes'] ?? null,

            'status' =>
                $data['status']
                ?? ContactStatus::ACTIVE->value,
        ]);
    }
}