<?php

namespace App\Filament\Resources\Galleries\Tables;

use App\Enums\Flag;
use App\Enums\Image;
use App\Models\Others\Gallery;
use Filament\Actions\BulkActionGroup;
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
                /*
                |--------------------------------------------------------------------------
                | IMAGE
                |--------------------------------------------------------------------------
                */
                ImageColumn::make('image')
                    ->label('Preview')
                    ->disk('public')
                    ->imageHeight(50)
                    ->width(50)
                    ->circular()
                    ->defaultImageUrl(asset('assets/img/placeholder.png'))
                    ->tooltip('Image preview'),

                /*
                |--------------------------------------------------------------------------
                | TITLE (Replaces original_name)
                |--------------------------------------------------------------------------
                */
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->limit(40)
                    ->tooltip(fn($record) => $record->title)
                    ->icon('heroicon-o-photo')
                    ->wrap(),

                /*
                |--------------------------------------------------------------------------
                | TYPE (ENUM)
                |--------------------------------------------------------------------------
                */
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->formatStateUsing(fn($state) => optional(
                        $state instanceof Image ? $state : Image::coerce($state)
                    )->description),

                /*
                |--------------------------------------------------------------------------
                | STATUS (FLAG ENUM)
                |--------------------------------------------------------------------------
                */
                TextColumn::make('flag')
                    ->label('Status')
                    ->badge()
                    ->color(function ($state) {
                        $flag = $state instanceof Flag ? $state : Flag::coerce($state);

                        return match ($flag?->value) {
                            Flag::PUBLISHED => 'success',
                            Flag::PENDING_REVIEW => 'info',
                            Flag::DRAFT => 'warning',
                            Flag::ARCHIVED => 'danger',
                            default => 'gray',
                        };
                    })
                    ->icon(function ($state) {
                        $flag = $state instanceof Flag ? $state : Flag::coerce($state);

                        return match ($flag?->value) {
                            Flag::PUBLISHED => 'heroicon-o-check-circle',
                            Flag::PENDING_REVIEW => 'heroicon-o-clock',
                            Flag::DRAFT => 'heroicon-o-pencil',
                            Flag::ARCHIVED => 'heroicon-o-archive-box',
                            default => 'heroicon-o-flag',
                        };
                    })
                    ->sortable()
                    ->formatStateUsing(
                        fn($state) =>
                        Flag::coerce($state)?->description
                    ),
                /*
                |--------------------------------------------------------------------------
                | COLOR (NEW FIELD)
                |--------------------------------------------------------------------------
                */
                TextColumn::make('color')
                    ->label('Color')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => $state),

                /*
                |--------------------------------------------------------------------------
                | ORDER (NEW FIELD)
                |--------------------------------------------------------------------------
                */
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                /*
                |--------------------------------------------------------------------------
                | DATES
                |--------------------------------------------------------------------------
                */
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('M j, Y')
                    ->since()
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            /*
            |--------------------------------------------------------------------------
            | SORTING (use order first)
            |--------------------------------------------------------------------------
            */
            ->defaultSort('order')

            /*
            |--------------------------------------------------------------------------
            | FILTERS (ENUM-BASED, NOT DB QUERY)
            |--------------------------------------------------------------------------
            */
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('type')
                    ->options(Image::asSelectArray())
                    ->multiple()
                    ->preload(),

                SelectFilter::make('flag')
                    ->options(Flag::asSelectArray())
                    ->multiple()
                    ->preload(),
            ])

            ->filtersFormColumns(2)

            /*
            |--------------------------------------------------------------------------
            | ACTIONS
            |--------------------------------------------------------------------------
            */
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | UI SETTINGS
            |--------------------------------------------------------------------------
            */
            ->emptyStateHeading('No Gallery Items')
            ->emptyStateDescription('Start by adding images to your gallery.')
            ->emptyStateIcon('heroicon-o-photo')

            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->poll('30s');
    }
}
