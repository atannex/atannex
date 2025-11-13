<?php

namespace App\Filament\Resources\Colors\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ColorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ColorColumn::make('hex')
                        ->label('Color')
                        ->grow(false)
                        ->alignCenter(),

                    Stack::make([
                        TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight('semibold')
                            ->size('sm'),

                        TextColumn::make('hex')
                            ->label('Hex Code')
                            ->searchable()
                            ->copyable()
                            ->copyMessage('Hex code copied!')
                            ->color('gray')
                            ->size('xs'),
                    ]),

                    TextColumn::make('created_at')
                        ->label('Created')
                        ->dateTime('M j, Y')
                        ->sortable()
                        ->toggleable()
                        ->color('gray')
                        ->size('xs')
                        ->grow(false),
                ])->from('md'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('recently_added')
                    ->label('Recently Added')
                    ->options([
                        'today' => 'Today',
                        'week' => 'This Week',
                        'month' => 'This Month',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'today' => $query->whereDate('created_at', today()),
                            'week' => $query->where('created_at', '>=', now()->subWeek()),
                            'month' => $query->where('created_at', '>=', now()->subMonth()),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-horizontal')
                    ->tooltip('Actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No colors yet')
            ->emptyStateDescription('Create your first color to get started.')
            ->emptyStateIcon('heroicon-o-swatch')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
