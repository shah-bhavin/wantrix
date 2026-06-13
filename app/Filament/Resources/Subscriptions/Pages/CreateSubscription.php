<?php

namespace App\Filament\Resources\Subscriptions\Pages;

use App\Filament\Resources\Subscriptions\SubscriptionResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Subscription;
use Illuminate\Validation\ValidationException;


class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Subscription::hasActiveSubscription($data['vendor_id'])) {
            throw ValidationException::withMessages([
                'data.vendor_id' => 'This vendor already has an active or trial subscription.',
            ]);
        }

        $data['created_by'] = auth()->id();

        return $data;
    }
}
