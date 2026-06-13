<?php

namespace App\Filament\Resources\SubscriptionChanges;

use App\Filament\Resources\SubscriptionChanges\Pages\CreateSubscriptionChange;
use App\Filament\Resources\SubscriptionChanges\Pages\EditSubscriptionChange;
use App\Filament\Resources\SubscriptionChanges\Pages\ListSubscriptionChanges;
use App\Filament\Resources\SubscriptionChanges\Schemas\SubscriptionChangeForm;
use App\Filament\Resources\SubscriptionChanges\Tables\SubscriptionChangesTable;
use App\Models\SubscriptionChange;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SubscriptionChangeResource extends Resource
{
    protected static ?string $model = SubscriptionChange::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Billing';
    
    public static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return SubscriptionChangeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionChangesTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptionChanges::route('/'),
            'create' => CreateSubscriptionChange::route('/create'),
            'edit' => EditSubscriptionChange::route('/{record}/edit'),
        ];
    }
}
