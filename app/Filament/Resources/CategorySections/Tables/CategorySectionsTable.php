<?php

namespace App\Filament\Resources\CategorySections\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ReplicateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class CategorySectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->weight('medium')
                    ->icon('heroicon-o-tag')
                    ->iconColor('primary')
                    ->copyable()
                    ->tooltip('Click to copy category name'),

                TextColumn::make('section.name')
                    ->label('Section')
                    ->sortable()
                    ->searchable()
                    ->weight('medium')
                    ->icon('heroicon-o-rectangle-stack')
                    ->iconColor('gray')
                    ->placeholder('No section assigned')
                    ->copyable()
                    ->tooltip('Click to copy section name'),

                TextColumn::make('position')
                    ->label('Position')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state == 0 => 'success',
                        $state < 5 => 'warning',
                        default => 'gray',
                    })
                    ->tooltip('Display order position'),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => true,
                        'heroicon-o-x-circle' => false,
                    ]),

                TextColumn::make('config')
                    ->label('Configuration')
                    ->limit(30)
                    ->tooltip(fn (?string $state): ?string => $state)
                    ->placeholder('No configuration')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
                    ->tooltip(fn (?string $state): ?string => $state),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
                    ->tooltip(fn (?string $state): ?string => $state),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('Not deleted')
                    ->color('danger'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->placeholder('All categories'),

                SelectFilter::make('section_id')
                    ->label('Section')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->placeholder('All sections'),

                Filter::make('is_active')
                    ->label('Status')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),

                Filter::make('no_section')
                    ->label('No Section Assigned')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->whereNull('section_id')),

                Filter::make('has_config')
                    ->label('Has Configuration')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('config')),

                TrashedFilter::make(),
            ])
            ->filtersFormColumns(3)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),
                    ReplicateAction::make()
                        ->excludeAttributes(['created_at', 'updated_at'])
                        ->color('success'),
                ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->tooltip('More actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    ForceDeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    RestoreBulkAction::make()
                        ->deselectRecordsAfterCompletion(),
                ])
                ->label('Bulk Actions'),
            ])
            ->defaultSort('position', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->poll('30s')
            ->searchable()
            ->searchOnBlur()
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->persistSearchInSession()
            ->recordUrl(null)
            ->emptyStateHeading('No category sections found')
            ->emptyStateDescription('Get started by creating your first category section.')
            ->emptyStateIcon('heroicon-o-rectangle-stack');
    }
}
