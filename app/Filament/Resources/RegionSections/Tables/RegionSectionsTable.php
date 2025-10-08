<?php

namespace App\Filament\Resources\RegionSections\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;

class RegionSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->filters(self::getFilters())
            ->recordActions(self::getRecordActions())
            ->toolbarActions(self::getToolbarActions());
    }

    /**
     * Define the table columns.
     */
    protected static function getColumns(): array
    {
        return [
            TextColumn::make('region.name')
                ->label('Region')
                ->description(fn($record) => 'Flag: ' . $record->flag)
                ->searchable(),

            TextColumn::make('section.name')
                ->label('Section')
                ->description(fn($record) => 'Slug: ' . $record->section->slug)
                ->searchable(),

            TextColumn::make('position')
                ->numeric()
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Created')
                ->formatStateUsing(fn($state) => $state?->diffForHumans() ?? 'N/A')
                ->sortable(),

            TextColumn::make('updated_at')
                ->label('Updated')
                ->formatStateUsing(fn($state) => $state?->diffForHumans() ?? 'N/A')
                ->sortable(),

            TextColumn::make('deleted_at')
                ->label('Deleted')
                ->formatStateUsing(fn($state) => $state?->diffForHumans() ?? 'N/A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * Define the filters.
     */
    protected static function getFilters(): array
    {
        return [
            TrashedFilter::make(),
        ];
    }

    /**
     * Define record actions.
     */
    protected static function getRecordActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    /**
     * Define toolbar bulk actions.
     */
    protected static function getToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]),
        ];
    }
}
