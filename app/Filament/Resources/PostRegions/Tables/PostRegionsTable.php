<?php

namespace App\Filament\Resources\PostRegions\Tables;

use Filament\Tables\Table;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class PostRegionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('post.title')
                    ->label('Post Title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->wrap()
                    ->limit(50)
                    ->tooltip(fn($record) => $record->post?->title),

                TextColumn::make('region.name')
                    ->label('Region')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable()
                    ->description(fn($state) => $state?->diffForHumans())
                    ->size('sm'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->description(fn($state) => $state?->diffForHumans())
                    ->size('sm'),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->relationship('region', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Region'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton(),
                EditAction::make()
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->poll('30s')
            ->deferLoading()
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->persistSearchInSession();
    }
}
