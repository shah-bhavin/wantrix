<?php

namespace App\Filament\Resources\SubscriptionChanges\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SubscriptionChangeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('vendor_id')
                    ->required()
                    ->numeric(),
                TextInput::make('old_subscription_id')
                    ->numeric(),
                TextInput::make('new_subscription_id')
                    ->numeric(),
                TextInput::make('old_plan_id')
                    ->numeric(),
                TextInput::make('new_plan_id')
                    ->numeric(),
                TextInput::make('change_type')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}
