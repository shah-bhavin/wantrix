<?php

namespace App\Filament\Resources\Vendors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vendor Information')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('email')
                        ->email(),

                    TextInput::make('phone'),

                    TextInput::make('gst_number'),

                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'active' => 'Active',
                            'suspended' => 'Suspended',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required(),

                    Textarea::make('address')
                        ->rows(3),

                    FileUpload::make('logo')
                        ->image()
                        ->directory('vendors'),
                ])
                ->columns(2),

            ])->columns(1);
    }
}
