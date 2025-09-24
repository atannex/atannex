<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\DeleteAction;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('user.image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user->name ?? 'Employee') . '&color=7F9CF5&background=EBF4FF')
                    ->imageSize(40)
                    ->toggleable(),

                TextColumn::make('code')
                    ->label('Employee #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->badge()
                    ->color('gray')
                    ->size('sm'),

                TextColumn::make('user.name')
                    ->label('Employee Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn($record) => $record->user->email ?? 'No email')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary'),

                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'on-leave',
                        'danger' => 'terminated',
                        'gray' => 'inactive',
                        'info' => 'probation',
                    ])
                    ->icons([
                        'heroicon-m-check-circle' => 'active',
                        'heroicon-m-pause-circle' => 'on-leave',
                        'heroicon-m-x-circle' => 'terminated',
                        'heroicon-m-minus-circle' => 'inactive',
                        'heroicon-m-clock' => 'probation',
                    ]),

                TextColumn::make('manager.user.name')
                    ->label('Manager')
                    ->searchable()
                    ->placeholder('No manager')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('purple')
                    ->toggleable(),

                TextColumn::make('salary')
                    ->label('Salary')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-banknotes')
                    ->iconColor('green'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Employment Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on-leave' => 'On Leave',
                        'terminated' => 'Terminated',
                        'probation' => 'Probation',
                    ])
                    ->multiple(),

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('departments', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                SelectFilter::make('manager_id')
                    ->label('Manager')
                    ->relationship('manager.user', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Filter::make('no_manager')
                    ->label('Without Manager')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereNull('manager_id')
                    )
                    ->toggle(),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete Employee'),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Employee'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ])
                    ->label('Actions'),
            ])
            ->defaultSort('user.name', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->poll('60s')
            ->searchPlaceholder('Search employees by name, number, or job title...')
            ->emptyStateHeading('No employees found')
            ->emptyStateDescription('Add your first employee to get started with HR management.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
