<?php

namespace App\Filament\Resources\PostTags\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Post-Tag Associations Table Configuration
 *
 * Manages the display and interaction of post-tag relationships
 * in the Filament admin panel.
 */
class PostTagsTable
{
    /**
     * Configure the post-tag associations table
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->alignCenter()
                    ->color('gray')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('post.title')
                    ->label('Post Title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->post?->title)
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->url(
                        fn ($record) => $record->post
                            ? route('filament.admin.resources.posts.edit', ['record' => $record->post])
                            : null
                    )
                    ->openUrlInNewTab()
                    ->description(
                        fn ($record) => $record->post?->slug
                            ? "Slug: {$record->post->slug}"
                            : null
                    )
                    ->wrap(),

                TextColumn::make('tag.name')
                    ->label('Tag Name')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-tag')
                    ->iconColor('success')
                    ->badge()
                    ->color('info')
                    ->url(
                        fn ($record) => $record->tag
                            ? route('filament.admin.resources.tags.edit', ['record' => $record->tag])
                            : null
                    )
                    ->openUrlInNewTab()
                    ->description(
                        fn ($record) => $record->tag?->slug
                            ? "Slug: {$record->tag->slug}"
                            : null
                    ),

                TextColumn::make('posts_count')
                    ->label('Tag Usage')
                    ->state(fn ($record) => $record->tag?->posts_count ?? 0)
                    ->alignCenter()
                    ->badge()
                    ->suffix(' posts')
                    ->color(fn (int $state) => match (true) {
                        $state === 0 => 'gray',
                        $state < 5 => 'warning',
                        $state < 20 => 'info',
                        default => 'success',
                    })
                    ->icon('heroicon-o-chart-bar')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Associated On')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->description(fn ($record) => $record->created_at?->diffForHumans())
                    ->icon('heroicon-o-calendar')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('post')
                    ->label('Filter by Post')
                    ->relationship('post', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->placeholder('All Posts')
                    ->optionsLimit(50),

                SelectFilter::make('tag')
                    ->label('Filter by Tag')
                    ->relationship('tag', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->placeholder('All Tags')
                    ->optionsLimit(50),

                SelectFilter::make('tag_usage')
                    ->label('Tag Popularity')
                    ->options([
                        'unused' => 'Unused (0 posts)',
                        'low' => 'Low (1-4 posts)',
                        'medium' => 'Medium (5-19 posts)',
                        'high' => 'High (20+ posts)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value'] ?? null) {
                            'unused' => $query->whereHas('tag', function (Builder $q) {
                                $q->has('posts', '=', 0);
                            }),
                            'low' => $query->whereHas('tag', function (Builder $q) {
                                $q->has('posts', '>=', 1)->has('posts', '<', 5);
                            }),
                            'medium' => $query->whereHas('tag', function (Builder $q) {
                                $q->has('posts', '>=', 5)->has('posts', '<', 20);
                            }),
                            'high' => $query->whereHas('tag', function (Builder $q) {
                                $q->has('posts', '>=', 20);
                            }),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->color('info'),
                    EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->color('warning'),
                    DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Remove Association')
                        ->modalDescription('Are you sure you want to remove this tag from the post?'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Actions')
                    ->color('gray'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->modalHeading('Remove Associations')
                        ->modalDescription('Are you sure you want to remove these tag associations? This will unlink the tags from their posts.'),
                ])
                    ->label('Bulk Actions'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No post-tag associations found')
            ->emptyStateDescription('Start by creating a new association between a post and a tag.')
            ->emptyStateIcon('heroicon-o-link')
            ->striped()
            ->paginated([10, 25, 50, 100, 'all'])
            ->deferLoading()
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession();
    }
}
