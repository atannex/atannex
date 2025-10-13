<?php

namespace App\Filament\Resources\Sections\Tables;

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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Section Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn($record) => $record->slug)
                    ->copyable()
                    ->copyMessage('Section name copied')
                    ->icon('heroicon-o-squares-2x2')
                    ->grow(),

                TextColumn::make('slug')
                    ->label('URL Slug')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->size('sm')
                    ->color('gray')
                    ->tooltip(fn($state) => "Path: /{$state}")
                    ->prefix('/')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('flag')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'inactive' => 'danger',
                        'draft' => 'gray',
                        default => 'info',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'active' => 'heroicon-o-check-circle',
                        'pending' => 'heroicon-o-clock',
                        'inactive' => 'heroicon-o-x-circle',
                        'draft' => 'heroicon-o-document',
                        default => 'heroicon-o-flag',
                    })
                    ->sortable()
                    ->searchable(),

                IconColumn::make('deleted_at')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-trash')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->alignCenter()
                    ->tooltip(fn($record) => $record->deleted_at ?
                        'Deleted: ' . $record->deleted_at->diffForHumans() : 'Active'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
                    ->description(fn($record) => $record->created_at->format('g:i A'))
                    ->icon('heroicon-o-plus-circle'),

                TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since()
                    ->description(fn($record) => $record->updated_at->format('g:i A'))
                    ->icon('heroicon-o-pencil-square'),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label('Status Filter')
                    ->placeholder('Active Sections')
                    ->trueLabel('Only Deleted')
                    ->falseLabel('Exclude Deleted')
                    ->native(false),

                SelectFilter::make('flag')
                    ->label('Filter by Status')
                    ->options(fn() => \App\Enums\Flag::labels())
                    ->native(false)
                    ->multiple()
                    ->placeholder('All Statuses'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),
                    DeleteAction::make(),
                    RestoreAction::make()
                        ->color('success'),
                    ForceDeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Actions')
                    ->color('gray'),
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
            ->emptyStateHeading('No sections found')
            ->emptyStateDescription('Create your first section to organize your content.')
            ->emptyStateIcon('heroicon-o-squares-2x2')
            ->recordUrl(null)
            ->paginated([10, 25, 50, 100]);
    }
}
