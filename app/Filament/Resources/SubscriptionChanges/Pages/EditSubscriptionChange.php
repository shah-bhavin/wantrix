<?php

namespace App\Filament\Resources\SubscriptionChanges\Pages;

use App\Filament\Resources\SubscriptionChanges\SubscriptionChangeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptionChange extends EditRecord
{
    protected static string $resource = SubscriptionChangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
