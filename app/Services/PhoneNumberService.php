<?php

namespace App\Services;

class PhoneNumberService
{
    /**
     * Normalize a phone number for storage and WhatsApp sending.
     *
     * Examples:
     * 07405217574      -> 917405217574
     * 7405217574       -> 917405217574
     * +91 74052 17574  -> 917405217574
     * 91-7405217574    -> 917405217574
     */
    public function normalize(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        // Keep digits only.
        $phone = preg_replace('/\D+/', '', $phone);

        if (blank($phone)) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Indian mobile number
        |--------------------------------------------------------------------------
        |
        | Remove one leading zero, then add India country code
        | when a 10-digit mobile number is supplied.
        */

        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            $phone = substr($phone, 1);
        }

        if (strlen($phone) === 10) {
            return '91' . $phone;
        }

        /*
        |--------------------------------------------------------------------------
        | Already contains a country code
        |--------------------------------------------------------------------------
        */

        return $phone;
    }
}