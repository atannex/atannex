<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegionSectionWidgets\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\{
    EditAction,
    BulkActionGroup,
    DeleteBulkAction,
    ForceDeleteBulkAction,
    RestoreBulkAction
};

/**
 * Class RegionSectionWidgetsTable
 *
 * Defines the Filament table configuration for Region Section Widgets.
 * Provides consistent column definitions, filters, and bulk actions
 * optimized for usability and maintainability.
 *
 * @package App\Filament\Resources\RegionSectionWidgets\Tables
 */
class RegionSectionWidgetsTable
{
    /**
     * Configure the Filament table for RegionSectionWidgets.
     *
     * @param Table $table
     * @return Table
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('region.name')
                    ->label('Region')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('section.name')
                    ->label('Section')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('widget.name')
                    ->label('Widget')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('flag')
                    ->label('Flag')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'secondary',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('position')
                    ->numeric()
                    ->sortable()
                    ->label('Position'),

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

            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
