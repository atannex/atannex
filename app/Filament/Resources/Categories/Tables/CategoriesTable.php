<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoriesTable
{
    /**
     * Configure the categories table with professional features and improved UX.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->imageSize(40)
                    ->defaultImageUrl(url('/images/placeholder-category.png'))
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->copyable()
                    ->tooltip('Click to copy')
                    ->wrap(),

                TextColumn::make('slug_path')
                    ->label('Full Path')
                    ->searchable()
                    ->fontFamily('mono')
                    ->size('sm')
                    ->color('gray')
                    ->copyable()
                    ->wrap(),

                TextColumn::make('flag')
                    ->badge()
                    ->label('Flag')
                    ->searchable()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'featured',
                        'danger' => 'inactive',
                        'primary' => 'new',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'active',
                        'heroicon-o-star' => 'featured',
                        'heroicon-o-x-circle' => 'inactive',
                        'heroicon-o-plus-circle' => 'new',
                    ]),

                TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Root Category')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('children_count')
                    ->label('Subcategories')
                    ->counts('children')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->placeholder('Not published')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn ($record) => $record->published_at?->format('F j, Y \a\t g:i A')),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y \a\t g:i A')),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn ($record) => $record->updated_at->format('F j, Y \a\t g:i A')),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn ($record) => $record->deleted_at?->format('F j, Y \a\t g:i A')),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                TrashedFilter::make()
                    ->label('Deleted Categories'),

                SelectFilter::make('flag')
                    ->label('Status Flag')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'featured' => 'Featured',
                        'new' => 'New',
                    ])
                    ->multiple(),

                SelectFilter::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                SelectFilter::make('published')
                    ->label('Publication Status')
                    ->options([
                        'published' => 'Published',
                        'unpublished' => 'Unpublished',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            in_array('published', $data),
                            fn (Builder $query): Builder => $query->whereNotNull('published_at'),
                        )->when(
                            in_array('unpublished', $data),
                            fn (Builder $query): Builder => $query->whereNull('published_at'),
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Categories')
                        ->modalDescription('Are you sure you want to delete these categories? This action cannot be undone.')
                        ->modalSubmitActionLabel('Delete Categories'),

                    ForceDeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Permanently Delete Categories')
                        ->modalDescription('Are you sure you want to permanently delete these categories? This action cannot be undone.')
                        ->modalSubmitActionLabel('Permanently Delete'),

                    RestoreBulkAction::make()
                        ->modalHeading('Restore Categories')
                        ->modalDescription('Are you sure you want to restore these categories?')
                        ->modalSubmitActionLabel('Restore Categories'),
                ])
                    ->label('Actions')
                    ->iconButton(),
            ])
            ->emptyStateHeading('No categories found')
            ->emptyStateDescription('Get started by creating your first category.')
            ->emptyStateIcon('heroicon-o-folder')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->extremePaginationLinks()
            ->recordTitleAttribute('name');
    }
}
