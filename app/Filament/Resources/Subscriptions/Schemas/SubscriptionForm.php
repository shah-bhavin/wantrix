<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use App\Enums\SubscriptionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Section::make('Subscription Information')
                    ->schema([
                        Select::make('vendor_id')
                            ->relationship('vendor', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('plan_id')
                            ->relationship('plan', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($get, $set) {

                                if (! $get('plan_id') || ! $get('starts_at')) {
                                    return;
                                }

                                $plan = Plan::find($get('plan_id'));

                                if (! $plan) {
                                    return;
                                }

                                $set(
                                    'trial_ends_at',
                                    Carbon::parse($get('starts_at'))
                                        ->addDays($plan->trial_days)
                                );
                            }),
                        Select::make('status')
                            ->options(SubscriptionStatus::class)
                            ->default('trial')
                            ->required(),
                    ]),
                
                Section::make('Dates')
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($get, $set) {

                                if (! $get('plan_id') || ! $get('starts_at')) {
                                    return;
                                }

                                $plan = Plan::find($get('plan_id'));

                                if (! $plan) {
                                    return;
                                }

                                $set(
                                    'trial_ends_at',
                                    Carbon::parse($get('starts_at'))
                                        ->addDays($plan->trial_days)
                                );
                            }),
                        DateTimePicker::make('ends_at'),
                        DateTimePicker::make('trial_ends_at')
                            ->disabled(),
                        DateTimePicker::make('cancelled_at'),
                    ]),
                Section::make('Audit')
                    ->schema([
                        TextInput::make('created_by')
                        ->numeric(),
                    ]),
            ]);
            
    }
}
