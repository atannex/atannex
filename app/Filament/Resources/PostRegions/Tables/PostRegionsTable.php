<?php

namespace App\Filament\Resources\PostRegions\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\{
    BulkActionGroup,
    DeleteBulkAction,
    EditAction
};

class PostRegionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('post.title')
                    ->label('Post Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('region.name')
                    ->label('Region Name')
                    ->searchable()
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
            ])
            ->filters([
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
