<?php

namespace App\Filament\Resources\SubscriptionChanges\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionChangesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendor.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('oldPlan.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('newPlan.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('change_type')
                    ->badge(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('change_type') // Replace 'type' with your actual database column name
                    ->options([
                        'upgrade' => 'Upgrade',
                        'downgrade' => 'Downgrade',
                        'renewal' => 'Renewal',
                        'cancellation' => 'Cancellation',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
