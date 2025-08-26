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
use Carbon\Carbon;
use Filament\Actions\DeleteAction;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('user.avatar')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user->name ?? 'Employee') . '&color=7F9CF5&background=EBF4FF')
                    ->imageSize(40)
                    ->toggleable(),

                TextColumn::make('employee_number')
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

                TextColumn::make('job_title')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn($record) => $record->department?->name)
                    ->icon('heroicon-m-briefcase')
                    ->iconColor('gray'),

                TextColumn::make('employment_type')
                    ->badge()
                    ->label('Type')
                    ->searchable()
                    ->sortable()
                    ->colors([
                        'success' => 'full-time',
                        'warning' => 'part-time',
                        'info' => 'contract',
                        'secondary' => 'intern',
                        'danger' => 'temporary',
                    ])
                    ->icons([
                        'heroicon-m-clock' => 'full-time',
                        'heroicon-m-clock' => 'part-time',
                        'heroicon-m-document-text' => 'contract',
                        'heroicon-m-academic-cap' => 'intern',
                        'heroicon-m-calendar' => 'temporary',
                    ]),

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

                TextColumn::make('hire_date')
                    ->label('Hire Date')
                    ->date('M j, Y')
                    ->sortable()
                    ->description(function ($record) {
                        if ($record->hire_date) {
                            $years = Carbon::parse($record->hire_date)->diffInYears(now());
                            $months = Carbon::parse($record->hire_date)->diffInMonths(now()) % 12;
                            return $years > 0 ? sprintf('%sy %dm tenure', $years, $months) : $months . 'm tenure';
                        }

                        return null;
                    })
                    ->icon('heroicon-m-calendar-days')
                    ->iconColor('green'),

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

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable()
                    ->placeholder('—')
                    ->icon('heroicon-m-phone')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->created_at->format('F j, Y g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->updated_at->format('F j, Y g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),
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

                SelectFilter::make('employment_type')
                    ->label('Employment Type')
                    ->options([
                        'full-time' => 'Full Time',
                        'part-time' => 'Part Time',
                        'contract' => 'Contract',
                        'intern' => 'Intern',
                        'temporary' => 'Temporary',
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

                SelectFilter::make('work_location')
                    ->label('Work Location')
                    ->options([
                        'office' => 'Office',
                        'remote' => 'Remote',
                        'hybrid' => 'Hybrid',
                        'field' => 'Field',
                    ])
                    ->multiple(),

                Filter::make('new_hires')
                    ->label('New Hires (Last 30 Days)')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->where('hire_date', '>=', now()->subDays(30))
                    )
                    ->toggle(),

                Filter::make('long_tenure')
                    ->label('Long Tenure (5+ Years)')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->where('hire_date', '<=', now()->subYears(5))
                    )
                    ->toggle(),

                Filter::make('no_manager')
                    ->label('Without Manager')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereNull('manager_id')
                    )
                    ->toggle(),

                Filter::make('birthday_this_month')
                    ->label('Birthday This Month')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereMonth('date_of_birth', now()->month)
                    )
                    ->toggle(),

                Filter::make('probation_ending')
                    ->label('Probation Ending Soon')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->where('status', 'probation')
                            ->where('hire_date', '<=', now()->subMonths(5))
                            ->where('hire_date', '>=', now()->subMonths(6))
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
