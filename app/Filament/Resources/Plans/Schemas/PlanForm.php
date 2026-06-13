<?php

namespace App\Filament\Resources\Plans\Schemas;

use App\Enums\PlanStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('monthly_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('yearly_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('max_users')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('max_contacts')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_whatsapp_numbers')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_campaigns_per_month')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_popular')
                    ->required(),
                Select::make('status')
                    ->options(PlanStatus::class)
                    ->default('active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_unlimited_users')
                ->label('Unlimited Users')
                ->default(false),

            Section::make('Primary')
            ->description('Primary')
            ->schema([
                Toggle::make('is_unlimited_contacts')
                    ->label('Unlimited Contacts')
                    ->default(false),

                Toggle::make('is_unlimited_whatsapp_numbers')
                    ->label('Unlimited WhatsApp Numbers')
                    ->default(false),

                Toggle::make('is_unlimited_campaigns')
                    ->label('Unlimited Campaigns')
                    ->default(false),

                TextInput::make('trial_days')
                    ->label('Trial Days')
                    ->numeric()
                    ->required()
                    ->default(0),
            ]),
            
                    
            ]);

            
    }
}
