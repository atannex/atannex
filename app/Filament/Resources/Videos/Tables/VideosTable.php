<?php

namespace App\Filament\Resources\Videos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

/**
 * Videos Table Configuration
 *
 * Defines the table structure, columns, filters, and actions for the videos resource.
 * Provides a comprehensive interface for viewing, searching, and managing video records.
 */
class VideosTable
{
    /**
     * Configure the videos table
     *
     * Sets up columns for display, search and sort functionality, filters,
     * and available actions for individual records and bulk operations.
     *
     * @param  Table  $table  The table instance to configure
     * @return Table The configured table with columns, filters, and actions
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->filters(self::getFilters())
            ->recordActions(self::getRecordActions())
            ->toolbarActions(self::getToolbarActions())
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    /**
     * Get the table columns configuration
     */
    private static function getColumns(): array
    {
        return [
            ImageColumn::make('image')
                ->label('Thumbnail')
                ->circular()
                ->imageSize(60),

            TextColumn::make('title')
                ->label('Title')
                ->searchable()
                ->sortable()
                ->limit(50)
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();

                    return strlen($state) > 50 ? $state : null;
                })
                ->wrap(),

            TextColumn::make('post.title')
                ->label('Post')
                ->searchable()
                ->sortable()
                ->badge()
                ->limit(50)
                ->color('success'),

            TextColumn::make('flag')
                ->label('Status')
                ->searchable()
                ->sortable()
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'published' => 'success',
                    'draft' => 'warning',
                    'archived' => 'danger',
                    default => 'gray',
                }),

            TextColumn::make('published_at')
                ->label('Published')
                ->dateTime('M j, Y g:i A')
                ->sortable()
                ->placeholder('Not published')
                ->toggleable(),

            TextColumn::make('updated_by')
                ->label('Last Updated By')
                ->numeric()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->placeholder('N/A'),

            TextColumn::make('created_at')
                ->label('Created')
                ->dateTime('M j, Y g:i A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->label('Last Modified')
                ->dateTime('M j, Y g:i A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('deleted_at')
                ->label('Deleted')
                ->dateTime('M j, Y g:i A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->placeholder('Active'),
        ];
    }

    /**
     * Get the table filters configuration
     */
    private static function getFilters(): array
    {
        return [
            TrashedFilter::make()
                ->label('Status')
                ->placeholder('All Videos')
                ->trueLabel('Only Trashed')
                ->falseLabel('Without Trashed')
                ->native(false),

            SelectFilter::make('flag')
                ->label('Publication Status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ])
                ->native(false)
                ->multiple(),

            SelectFilter::make('post')
                ->relationship('post', 'title')
                ->label('Post')
                ->searchable()
                ->preload()
                ->native(false)
                ->multiple(),
        ];
    }

    /**
     * Get the record actions configuration
     */
    private static function getRecordActions(): array
    {
        return [
            ViewAction::make()
                ->iconButton()
                ->tooltip('View Details'),

            EditAction::make()
                ->iconButton()
                ->tooltip('Edit Video'),
        ];
    }

    /**
     * Get the toolbar actions configuration
     */
    private static function getToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),

                RestoreBulkAction::make()
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),

                ForceDeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Permanently Delete Videos')
                    ->modalDescription('Are you sure you want to permanently delete these videos? This action cannot be undone.')
                    ->deselectRecordsAfterCompletion(),
            ])
                ->label('Bulk Actions')
                ->icon('heroicon-o-chevron-down'),
        ];
    }

    /**
     * Format duration in seconds to a human-readable format (HH:MM:SS or MM:SS)
     *
     * @param  int  $seconds  The duration in seconds
     * @return string The formatted duration string
     */
    private static function formatDuration(int $seconds): string
    {
        if ($seconds < 0) {
            return '00:00';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
