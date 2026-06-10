<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vendor_id')
                    ->relationship(name: 'vendor', titleAttribute: 'name')
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(
                        fn ($state) => filled($state)
                            ? bcrypt($state)
                            : null
                    )
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create'),

                Select::make('roles')
                    ->relationship(name: 'roles', titleAttribute: 'name')
                    ->multiple() // Allow assigning multiple roles to a user
                    ->preload()  // Instantly populates the roles list upon clicking
                    ->searchable(),

                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                FileUpload::make('avatar')
                    ->image()
                    ->directory('avatars')
                    ->avatar()
            ]);
    }
}
