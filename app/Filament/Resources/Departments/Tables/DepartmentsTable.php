<?php

namespace App\Filament\Resources\Departments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable('Copy department code')
                    ->size('sm'),

                TextColumn::make('name')
                    ->label('Department Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn($record) => $record->slug)
                    ->wrap(),

                TextColumn::make('parent.name')
                    ->label('Parent Department')
                    ->sortable()
                    ->searchable()
                    ->placeholder('—')
                    ->icon('heroicon-m-building-office-2')
                    ->iconColor('gray')
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'suspended',
                        'gray' => 'draft',
                    ])
                    ->icons([
                        'heroicon-m-check-circle' => 'active',
                        'heroicon-m-pause-circle' => 'inactive',
                        'heroicon-m-x-circle' => 'suspended',
                        'heroicon-m-clock' => 'draft',
                    ]),

                TextColumn::make('employees_count')
                    ->label('Employees')
                    ->counts('employees')
                    ->sortable()
                    ->numeric()
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->created_at->format('F j, Y g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->updated_at->format('F j, Y g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->deleted_at?->format('F j, Y g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label('Archived Departments'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'draft' => 'Draft',
                    ])
                    ->multiple(),

                SelectFilter::make('parent_id')
                    ->label('Parent Department')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Filter::make('has_manager')
                    ->label('Has Manager')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('manager_id'))
                    ->toggle(),

                Filter::make('no_employees')
                    ->label('Empty Departments')
                    ->query(fn(Builder $query): Builder => $query->doesntHave('employees'))
                    ->toggle(),

                Filter::make('created_this_month')
                    ->label('Created This Month')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('created_at', now()->month))
                    ->toggle(),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete Department'),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Department'),
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
                    ->label('Actions'),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->paginated([50, 100])
            ->defaultPaginationPageOption(25)
            ->poll('30s')
            ->searchPlaceholder('Search departments...')
            ->emptyStateHeading('No departments found')
            ->emptyStateDescription('Create your first department to get started.')
            ->emptyStateIcon('heroicon-o-building-office-2');
    }
}
