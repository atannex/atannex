<?php

namespace App\Filament\Resources\Users\Tables;

use Carbon\Carbon;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF')
                    ->imageSize(40)
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn($record) => $record->slug)
                    ->icon('heroicon-m-user')
                    ->iconColor('primary'),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-envelope')
                    ->iconColor('gray')
                    ->description(function ($record) {
                        if ($record->email_verified_at) {
                            return '✅ Verified ' . $record->email_verified_at->format('M j, Y');
                        }

                        return '⚠️ Unverified';
                    }),

                TextColumn::make('email_verified_at')
                    ->badge()
                    ->label('Verification')
                    ->getStateUsing(function ($record) {
                        return $record->email_verified_at ? 'verified' : 'unverified';
                    })
                    ->colors([
                        'success' => 'verified',
                        'danger' => 'unverified',
                    ])
                    ->icons([
                        'heroicon-m-check-badge' => 'verified',
                        'heroicon-m-exclamation-triangle' => 'unverified',
                    ])
                    ->toggleable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable()
                    ->placeholder('—')
                    ->icon('heroicon-m-phone')
                    ->iconColor('gray')
                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            $cleaned = preg_replace('/[^0-9]/', '', $state);
                            if (strlen($cleaned) === 10) {
                                return sprintf(
                                    '(%s) %s-%s',
                                    substr($cleaned, 0, 3),
                                    substr($cleaned, 3, 3),
                                    substr($cleaned, 6, 4)
                                );
                            }
                        }

                        return $state;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('date_of_birth')
                    ->label('Age')
                    ->date('M j, Y')
                    ->sortable()
                    ->description(function ($record) {
                        if ($record->date_of_birth) {
                            $age = Carbon::parse($record->date_of_birth)->age;
                            return $age . ' years old';
                        }

                        return null;
                    })
                    ->icon('heroicon-m-calendar-days')
                    ->iconColor('blue')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'suspended',
                        'gray' => 'pending',
                        'info' => 'restricted',
                    ])
                    ->icons([
                        'heroicon-m-check-circle' => 'active',
                        'heroicon-m-pause-circle' => 'inactive',
                        'heroicon-m-no-symbol' => 'suspended',
                        'heroicon-m-clock' => 'pending',
                        'heroicon-m-shield-exclamation' => 'restricted',
                    ]),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('info')
                    ->separator(', ')
                    ->placeholder('No roles assigned')
                    ->toggleable(),

                TextColumn::make('online_status')
                    ->label('Online Status')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->isOnline() ? 'Online' : 'Offline')
                    // ->description(fn($record) => $record->lastSeen())
                    ->colors([
                        'success' => 'Online',
                        'gray' => 'Offline',
                    ])
                    ->icons([
                        'heroicon-m-wifi' => 'Online',
                        'heroicon-m-no-symbol' => 'Offline',
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Registered')
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
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label('Archived Users'),

                SelectFilter::make('status')
                    ->label('Account Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending',
                        'restricted' => 'Restricted',
                    ])
                    ->multiple(),

                Filter::make('email_verified')
                    ->label('Email Verified')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at'))
                    ->toggle(),

                Filter::make('email_unverified')
                    ->label('Email Unverified')
                    ->query(fn(Builder $query): Builder => $query->whereNull('email_verified_at'))
                    ->toggle(),

                SelectFilter::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                        'prefer_not_to_say' => 'Prefer not to say',
                    ])
                    ->multiple(),

                SelectFilter::make('roles')
                    ->label('User Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),

                Filter::make('has_employee_record')
                    ->label('Has Employee Record')
                    ->query(fn(Builder $query): Builder => $query->has('employee'))
                    ->toggle(),

                Filter::make('recent_login')
                    ->label('Logged in Last 30 Days')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->where('last_login_at', '>=', now()->subDays(30))
                    )
                    ->toggle(),

                Filter::make('inactive_users')
                    ->label('Inactive (No Login 90+ Days)')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->where('last_login_at', '<=', now()->subDays(90))
                            ->orWhereNull('last_login_at')
                    )
                    ->toggle(),

                Filter::make('new_users')
                    ->label('New Users (Last 7 Days)')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->where('created_at', '>=', now()->subDays(7))
                    )
                    ->toggle(),

                Filter::make('birthday_this_month')
                    ->label('Birthday This Month')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereMonth('date_of_birth', now()->month)
                    )
                    ->toggle(),

                Filter::make('online')
                    ->label('Online Users')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereHas('sessions', fn($query) => $query->where('last_activity', '>=', now()->subSeconds(300)->timestamp))
                    )
                    ->toggle(),

                Filter::make('offline')
                    ->label('Offline Users')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereDoesntHave('sessions', fn($query) => $query->where('last_activity', '>=', now()->subSeconds(300)->timestamp))
                    )
                    ->toggle(),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete User Profile'),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit User'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('verify_email')
                        ->label('Verify Email')
                        ->icon('heroicon-m-check-badge')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['email_verified_at' => now()]);
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    Action::make('activate')
                        ->label('Activate Users')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'active']);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),

                    Action::make('deactivate')
                        ->label('Deactivate Users')
                        ->icon('heroicon-m-pause-circle')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'inactive']);
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

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
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->poll('60s')
            ->searchPlaceholder('Search users by name, email, or phone...')
            ->emptyStateHeading('No users found')
            ->emptyStateDescription('Create your first user account to get started.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
