<?php

namespace App\Filament\Resources\SocialMedia\Tables;

use Carbon\Carbon;
use App\Enums\Icon;
use App\Enums\Flag;
use App\Enums\Icons;
use App\Enums\Binding;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Traits\HasEnumColumnAndField;

class SocialMediaTable
{
    use HasEnumColumnAndField;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Account Label')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-identification')
                    ->iconColor('primary')
                    ->description(fn($record) => $record->url ? parse_url($record->url, PHP_URL_HOST) : null)
                    ->wrap(),

                HasEnumColumnAndField::makeEnumColumn(
                    name: 'owner_type',
                    enumClass: Binding::class,
                    withIcon: true
                )
                    ->label('Owner Type'),

                HasEnumColumnAndField::makeEnumColumn(
                    name: 'platform',
                    enumClass: Icon::class,
                    withIcon: false
                )
                    ->label('Platform Type'),

                TextColumn::make('url')
                    ->label('Profile URL')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn($record) => $record->url)
                    ->copyable()
                    ->copyMessage('URL copied to clipboard!')
                    ->copyMessageDuration(1500)
                    ->url(fn($record) => $record->url, shouldOpenInNewTab: true)
                    ->icon('heroicon-m-link')
                    ->iconColor('gray')
                    ->placeholder('No URL provided')
                    ->formatStateUsing(fn($state) => $state ? str_replace(['https://', 'http://'], '', $state) : null),

                HasEnumColumnAndField::makeEnumColumn(
                    name: 'flag',
                    enumClass: Flag::class
                )
                    ->label('Flag'),

                TextColumn::make('order')
                    ->label('Order')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-m-bars-3')
                    ->iconPosition('before')
                    ->formatStateUsing(fn($state) => '#' . $state),

                ToggleColumn::make('is_global')->label('Global'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->created_at->format('F j, Y g:i:s A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-calendar-days')
                    ->iconColor('gray'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->updated_at->format('F j, Y g:i:s A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-pencil-square')
                    ->iconColor('gray'),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-trash')
                    ->iconColor('danger'),
            ])
            ->filters([
                SelectFilter::make('platform')
                    ->label('Platform')
                    ->options(Icon::asSelectArray())
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('flag')
                    ->label('Status')
                    ->options(Flag::labels())
                    ->multiple()
                    ->searchable()
                    ->preload(),

                SelectFilter::make('owner_type')
                    ->label('Owner Type')
                    ->options(Binding::labels())
                    ->multiple(),

                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label('Created from'),
                        DatePicker::make('created_until')
                            ->label('Created until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon('heroicon-m-eye')
                        ->color('info'),
                    EditAction::make()
                        ->icon('heroicon-m-pencil-square')
                        ->color('warning'),
                    DeleteAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger'),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->tooltip('More actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-m-trash'),
                    ForceDeleteBulkAction::make()
                        ->icon('heroicon-m-x-mark'),
                    RestoreBulkAction::make()
                        ->icon('heroicon-m-arrow-path'),
                ])
                    ->label('Bulk Actions'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Social Media')
                    ->icon('heroicon-m-plus')
                    ->color('primary')
                    ->tooltip('Create a new social media account'),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->paginationPageOptions([10, 25, 50, 100, 'all'])
            ->striped()
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->persistSearchInSession()
            ->searchOnBlur()
            ->extremePaginationLinks()
            ->emptyStateHeading('No Social Media Accounts Found')
            ->emptyStateDescription('Get started by creating your first social media account link. You can manage multiple platforms and assign them to different owners.')
            ->emptyStateIcon('heroicon-o-share')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Create Social Media Account')
                    ->icon('heroicon-m-plus')
                    ->color('primary')
                    ->size('lg'),
            ])
            ->persistFiltersInSession()
            ->filtersFormColumns(2)
            ->filtersFormMaxHeight('400px');
    }
}
