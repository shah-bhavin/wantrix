<?php

namespace App\Data\Meta;

class EmbeddedSignupResult
{
    public function __construct(

        public readonly bool $success,

        public readonly ?string $accessToken,

        public readonly ?string $businessId,

        public readonly ?string $wabaId,

        public readonly ?string $phoneNumberId,

        public readonly ?string $businessName,

        public readonly ?string $phoneNumber,

        public readonly ?string $verifiedName,

        public readonly ?string $error = null,

        public readonly array $raw = [],

    ) {}

    public static function failure(
        string $message,
        array $raw = []
    ): self {

        return new self(

            success: false,

            accessToken: null,

            businessId: null,

            wabaId: null,

            phoneNumberId: null,

            businessName: null,

            phoneNumber: null,

            verifiedName: null,

            error: $message,

            raw: $raw,
        );
    }

    public static function success(

        string $accessToken,

        string $businessId,

        string $wabaId,

        string $phoneNumberId,

        string $businessName,

        string $phoneNumber,

        string $verifiedName,

        array $raw = []

    ): self {

        return new self(

            success: true,

            accessToken: $accessToken,

            businessId: $businessId,

            wabaId: $wabaId,

            phoneNumberId: $phoneNumberId,

            businessName: $businessName,

            phoneNumber: $phoneNumber,

            verifiedName: $verifiedName,

            raw: $raw,
        );
    }
}