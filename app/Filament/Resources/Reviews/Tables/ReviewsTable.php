<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Enums\Flag;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reviewable_type')
                    ->label('Entity Type')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-o-document-text')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->description(fn($record) => "ID: {$record->reviewable_id}")
                    ->tooltip('Type of entity being reviewed'),

                TextColumn::make('reviewable_id')
                    ->label('Entity ID')
                    ->numeric()
                    ->sortable()
                    ->toggleable()
                    ->alignCenter(),

                TextColumn::make('content')
                    ->label('Review')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= 50) {
                            return null;
                        }

                        return $state;
                    })
                    ->searchable()
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('reviewer_rating')
                    ->label('Rating')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    })
                    ->icon(fn(int $state): string => match (true) {
                        $state >= 4 => 'heroicon-o-star',
                        $state >= 3 => 'heroicon-o-hand-thumb-up',
                        default => 'heroicon-o-hand-thumb-down',
                    })
                    ->formatStateUsing(fn($state) => number_format($state, 1) . ' / 5.0')
                    ->weight(FontWeight::Bold),

                TextColumn::make('flag')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Flag::APPROVED => 'success',
                        Flag::PENDING_REVIEW => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        Flag::APPROVED => 'heroicon-o-check-circle',
                        Flag::PENDING_REVIEW => 'heroicon-o-clock',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold),

                TextColumn::make('user.name')
                    ->label('User')
                    ->icon('heroicon-o-user-circle')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Guest')
                    ->description(fn($record) => $record->reviewer_name ?? null)
                    ->tooltip('Registered user or guest reviewer'),

                TextColumn::make('reviewer_name')
                    ->label('Guest Name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('N/A')
                    ->icon('heroicon-o-user'),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-globe-alt')
                    ->copyable()
                    ->copyMessage('IP address copied!')
                    ->copyMessageDuration(1500)
                    ->placeholder('Unknown')
                    ->fontFamily('mono')
                    ->size(TextSize::Small),

                IconColumn::make('deleted_at')
                    ->label('Deleted')
                    ->boolean()
                    ->trueIcon('heroicon-o-trash')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-calendar')
                    ->since()
                    ->description(fn($record) => $record->created_at->format('M j, Y g:i A'))
                    ->tooltip(fn($record) => $record->created_at->format('l, F j, Y \a\t g:i A')),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-arrow-path')
                    ->since()
                    ->description(fn($record) => $record->updated_at->format('M j, Y g:i A'))
                    ->tooltip(fn($record) => $record->updated_at->format('l, F j, Y \a\t g:i A')),
            ])
            ->filters([
                SelectFilter::make('flag')
                    ->label('Status')
                    ->options(Flag::asSelectArray())
                    ->multiple()
                    ->preload()
                    ->indicator('Status'),

                SelectFilter::make('reviewer_rating')
                    ->label('Rating')
                    ->options([
                        '5' => '⭐⭐⭐⭐⭐ (5 Stars)',
                        '4' => '⭐⭐⭐⭐ (4 Stars)',
                        '3' => '⭐⭐⭐ (3 Stars)',
                        '2' => '⭐⭐ (2 Stars)',
                        '1' => '⭐ (1 Star)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $rating): Builder => $query->where('reviewer_rating', '>=', $rating)
                                ->where('reviewer_rating', '<', (int)$rating + 1),
                        );
                    })
                    ->indicator('Rating'),

                SelectFilter::make('reviewable_type')
                    ->label('Entity Type')
                    ->preload()
                    ->indicator('Entity Type'),

                TrashedFilter::make()
                    ->label('Deleted Reviews')
                    ->native(false),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->persistFiltersInSession()
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->iconButton()
                        ->tooltip('View Review'),
                    EditAction::make()
                        ->iconButton()
                        ->tooltip('Edit Review'),
                    DeleteAction::make()
                        ->iconButton()
                        ->tooltip('Delete Review'),
                    RestoreAction::make()
                        ->iconButton()
                        ->tooltip('Restore Review'),
                    ForceDeleteAction::make()
                        ->iconButton()
                        ->tooltip('Permanently Delete'),
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
            ->emptyStateHeading('No reviews found')
            ->emptyStateDescription('Reviews will appear here once they are created.')
            ->emptyStateIcon('heroicon-o-star')
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->poll('30s');
    }
}
