<?php

namespace App\Filament\Resources\Galleries\Tables;

use Filament\Actions\BulkActionGroup;
use App\Models\Others\Gallery;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class GalleriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Preview')
                    ->disk('public')
                    ->imageHeight(50)
                    ->width(50)
                    ->circular()
                    ->defaultImageUrl(asset('assets/img/placeholder.png'))
                    ->tooltip('Image preview'),

                TextColumn::make('original_name')
                    ->label('Filename')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->limit(40)
                    ->tooltip(fn($record) => $record->original_name)
                    ->icon('heroicon-o-document')
                    ->iconColor('gray')
                    ->copyable()
                    ->copyMessage('Filename copied!')
                    ->copyMessageDuration(1500)
                    ->wrap(),

                TextColumn::make('type')
                    ->label('Image Type')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-tag')
                    ->formatStateUsing(fn(string $state): string => str($state)->headline()),

                TextColumn::make('flag')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                        'pending' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match (strtolower($state)) {
                        'published' => 'heroicon-o-check-circle',
                        'draft' => 'heroicon-o-pencil',
                        'archived' => 'heroicon-o-archive-box',
                        'pending' => 'heroicon-o-clock',
                        default => 'heroicon-o-flag',
                    })
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => str($state)->headline()),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-calendar')
                    ->iconColor('success')
                    ->tooltip(fn($record) => $record->created_at?->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-pencil-square')
                    ->iconColor('warning')
                    ->tooltip(fn($record) => $record->updated_at?->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->iconColor('danger')
                    ->tooltip(fn($record) => $record->deleted_at?->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make()
                    ->label('Archived Images'),

                SelectFilter::make('type')
                    ->label('Image Type')
                    ->options(function () {
                        return Gallery::query()
                            ->select('type')
                            ->distinct()
                            ->pluck('type', 'type')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->multiple(),

                SelectFilter::make('flag')
                    ->label('Status')
                    ->options(function () {
                        return Gallery::query()
                            ->select('flag')
                            ->distinct()
                            ->pluck('flag', 'flag')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->filtersFormColumns(2)
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Image'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Move to Trash')
                        ->icon('heroicon-o-trash')
                        ->color('warning'),

                    ForceDeleteBulkAction::make()
                        ->label('Delete Permanently')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger'),

                    RestoreBulkAction::make()
                        ->label('Restore')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success'),
                ])
                    ->label('Bulk Actions'),
            ])
            ->emptyStateHeading('No Images Found')
            ->emptyStateDescription('Upload your first image to start building your gallery.')
            ->emptyStateIcon('heroicon-o-photo')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->extremePaginationLinks()
            ->poll('30s');
    }
}
