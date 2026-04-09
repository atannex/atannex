<?php

declare(strict_types=1);

namespace App\Filament\Resources\Regions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RegionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                /**
                 * Identity
                 */
                TextColumn::make('name')
                    ->label('Region Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable()
                    ->searchable(),

                /**
                 * Classification
                 */
                TextColumn::make('territory')
                    ->label('Territory Level')
                    ->badge()
                    ->searchable(),

                TextColumn::make('flag')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                /**
                 * Hierarchy / Organization
                 */
                TextColumn::make('position')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('parent.name')
                    ->label('Parent Region')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('slug_path')
                    ->label('Hierarchy Path')
                    ->toggleable()
                    ->limit(40)
                    ->tooltip(fn($record) => $record->slug_path),

                /**
                 * System Dates
                 */
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            /**
             * Filters
             */
            ->filters([
                TrashedFilter::make(),
            ])

            /**
             * Row Actions
             */
            ->recordActions([
                EditAction::make(),
            ])

            /**
             * Bulk Actions
             */
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
