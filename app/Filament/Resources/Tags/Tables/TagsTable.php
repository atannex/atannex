<?php

namespace App\Filament\Resources\Tags\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

/**
 * Tags Table Configuration
 *
 * Defines the table structure, columns, filters, and actions
 * for managing tags in the Filament admin panel.
 */
class TagsTable
{
    /**
     * Configure the tags table
     *
     * @param Table $table
     * @return Table
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tag Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-o-tag')
                    ->copyable()
                    ->copyMessage('Tag name copied')
                    ->tooltip('Click to copy')
                    ->description(fn ($record) => $record->description
                        ? Str::limit($record->description, 50)
                        : null
                    ),

                TextColumn::make('slug')
                    ->label('URL Slug')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-link')
                    ->copyable()
                    ->copyMessage('Slug copied')
                    ->color('gray')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('posts_count')
                    ->label('Posts')
                    ->counts('posts')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state === 0 => 'gray',
                        $state < 5 => 'warning',
                        $state < 20 => 'info',
                        default => 'success',
                    })
                    ->icon('heroicon-o-document-text')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->description(fn ($record) => $record->created_at->diffForHumans())
                    ->icon('heroicon-o-calendar')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-clock')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label('Trashed Records')
                    ->placeholder('Without Trashed')
                    ->trueLabel('With Trashed')
                    ->falseLabel('Only Trashed'),

                SelectFilter::make('usage')
                    ->label('Tag Usage')
                    ->options([
                        'unused' => 'Unused (0 posts)',
                        'low' => 'Low Usage (1-4 posts)',
                        'medium' => 'Medium Usage (5-19 posts)',
                        'high' => 'High Usage (20+ posts)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value'] ?? null) {
                            'unused' => $query->has('posts', '=', 0),
                            'low' => $query->has('posts', '>=', 1)->has('posts', '<', 5),
                            'medium' => $query->has('posts', '>=', 5)->has('posts', '<', 20),
                            'high' => $query->has('posts', '>=', 20),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon('heroicon-o-eye'),
                    EditAction::make()
                        ->icon('heroicon-o-pencil'),
                    DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                    RestoreAction::make()
                        ->icon('heroicon-o-arrow-path'),
                    ForceDeleteAction::make()
                        ->icon('heroicon-o-x-circle'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip('Actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation(),
                    RestoreBulkAction::make()
                        ->icon('heroicon-o-arrow-path')
                        ->requiresConfirmation(),
                    ForceDeleteBulkAction::make()
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Force Delete Tags')
                        ->modalDescription('Are you sure you want to permanently delete these tags? This action cannot be undone.'),
                ])
                ->label('Actions'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No tags found')
            ->emptyStateDescription('Create your first tag to get started.')
            ->emptyStateIcon('heroicon-o-tag')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->deferLoading();
    }
}
