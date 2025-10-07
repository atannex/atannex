<?php

namespace App\Filament\Resources\WidgetSections\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\{
    BulkActionGroup,
    DeleteBulkAction,
    EditAction,
    ForceDeleteBulkAction,
    RestoreBulkAction
};

class WidgetSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('section.regions.name')
                    ->label('Region Names')
                    ->sortable(),

                TextColumn::make('section.name')
                    ->label('Section Name')
                    ->description(fn($record) => "slug: {$record->section->slug}")
                    ->tooltip(fn($record) => sprintf('Section #%s: %s', $record->section->name, $record->section->slug))
                    ->sortable(),

                TextColumn::make('widget.name')
                    ->label('Widget Name')
                    ->description(fn($record) => "slug: {$record->widget->slug}")
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->formatStateUsing(fn($state) => $state?->diffForHumans() ?? 'N/A')
                    ->tooltip(fn($state) => $state?->format('M d, Y h:i A') ?? 'N/A')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->formatStateUsing(fn($state) => $state?->diffForHumans() ?? 'N/A')
                    ->tooltip(fn($state) => $state?->format('M d, Y h:i A') ?? 'N/A')
                    ->sortable(),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->formatStateUsing(fn($state) => $state ? $state->diffForHumans() : 'Active')
                    ->tooltip(fn($state) => $state?->format('M d, Y h:i A') ?? 'Active')
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
