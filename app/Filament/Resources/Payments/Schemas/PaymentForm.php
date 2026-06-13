<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Primary')
                ->description('Primary')
                ->schema([
                    Select::make('vendor_id')
                        ->relationship('vendor', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('subscription_id')
                        ->relationship('subscription', 'vendor_id')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('amount')
                        ->required()
                        ->numeric(),
                    TextInput::make('currency')
                        ->required()
                        ->default('INR'),
                ]),
                Section::make('Primary')
                ->description('Primary')
                ->schema([
                    Select::make('status')
                        ->options(PaymentStatus::class)
                        ->default('pending')
                        ->required(),
                    TextInput::make('gateway'),
                    TextInput::make('gateway_payment_id'),
                    DateTimePicker::make('paid_at'),
                ]),
                Section::make('Primary')
                ->description('Primary')
                ->schema([
                    TextInput::make('gateway_response'),

                ]),
            ]);
    }
}
