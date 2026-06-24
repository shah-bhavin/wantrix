<?php

namespace App\Actions\Contacts;

use App\Models\Contact;
use App\Models\Vendor;
use App\Enums\ContactStatus;

class CreateContactAction
{
    public function execute(Vendor $vendor, array $data): Contact
    {
        return Contact::create([
            'vendor_id' => $vendor->id,
            'name' => $data['name'],
            'phone_number' => $data['phone_number'] ?? $data['phone_number'] ?? null, // Fixed to use 'mobile' column
            'email' => $data['email'] ?? null,
            'company' => $data['company'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'country' => $data['country'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? ContactStatus::ACTIVE->value,
        ]);
    }
}
