<?php

namespace App\Filament\Resources\Regions\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RegionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Region Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn ($record) => $record->slug)
                    ->copyable()
                    ->copyMessage('Region name copied')
                    ->icon('heroicon-o-map-pin'),

                TextColumn::make('territory')
                    ->label('Territory')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('flag')
                    ->label('Flag')
                    ->formatStateUsing(fn ($state) => $state ? '🏴 '.$state : '-')
                    ->alignCenter()
                    ->toggleable(),

                TextColumn::make('parent.name')
                    ->label('Parent Region')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-folder-open'),

                TextColumn::make('slug_path')
                    ->label('Full Path')
                    ->searchable()
                    ->toggleable()
                    ->limit(40)
                    ->tooltip(fn ($state) => $state)
                    ->copyable()
                    ->fontFamily('mono')
                    ->size('xs'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
                    ->description(fn ($record) => $record->created_at->format('g:i A')),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label('Archived Status')
                    ->placeholder('All Regions')
                    ->trueLabel('Only Archived')
                    ->falseLabel('Without Archived')
                    ->native(false),

                SelectFilter::make('parent_id')
                    ->label('Parent Region')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder('All Regions'),

                // SelectFilter::make('territory')
                //     ->label('Filter by Territory')
                //     ->options(fn () => Region::distinct()->pluck('territory', 'territory'))
                //     ->searchable()
                //     ->native(false),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ])
                    ->label('Bulk Actions'),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->deferLoading()
            ->emptyStateHeading('No regions found')
            ->emptyStateDescription('Create your first region to get started.')
            ->emptyStateIcon('heroicon-o-map');
    }
}
