<?php

namespace App\Filament\Resources\SubscriptionChanges\Pages;

use App\Filament\Resources\SubscriptionChanges\SubscriptionChangeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptionChanges extends ListRecords
{
    protected static string $resource = SubscriptionChangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
