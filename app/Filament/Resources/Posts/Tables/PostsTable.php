<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Filament\Traits\HasVisibilityRules;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PostsTable
{
    use HasVisibilityRules;

    /**
     * Configure and return a Filament table for displaying and managing posts.
     *
     * The table is configured with columns (images, badges, timestamps, relations, counters),
     * filters (category, author, breaking/editor picks, archived, recently published),
     * toolbar actions (individual edit/delete and grouped bulk actions), empty state,
     * pagination, styling, and polling behavior.
     *
     * @param \Filament\Tables\Table $table The Filament Table instance to configure.
     * @return \Filament\Tables\Table The configured Table instance.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Featured Image')
                    ->disk('public')
                    ->imageHeight(50)
                    ->circular()
                    ->width(80)
                    ->extraImgAttributes(['class' => 'rounded-lg'])
                    ->defaultImageUrl(url('/assets/img/logo.png'))
                    ->tooltip('Featured Image'),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->limit(50)
                    ->tooltip(fn($record) => $record->title)
                    ->description(fn($record) => $record->description ? Str::limit($record->description, 60) : null)
                    ->wrap(),

                // TextColumn::make('is_breaking')
                //     ->label('Breaking')
                //     ->badge()
                //     ->color(fn($state) => $state ? 'danger' : 'gray')
                //     ->formatStateUsing(fn($state) => $state ? 'BREAKING' : 'Normal')
                //     ->icon(fn($state) => $state ? 'heroicon-o-bolt' : null)
                //     ->sortable()
                //     ->toggleable(),

                TextColumn::make('region.name')
                    ->label('Regions')
                    ->badge(),

                TextColumn::make('is_editor_pick')
                    ->label("Editor's Pick")
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn($state) => $state ? 'FEATURED' : 'Standard')
                    ->icon(fn($state) => $state ? 'heroicon-o-star' : null)
                    ->sortable()
                    ->toggleable()
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('author.user.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-user')
                    ->iconColor('gray')
                    ->description(function ($record) {
                        return empty($record->author?->user?->email)
                            ? null
                            : Str::limit($record->author->user->email, 60);
                    })
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copied!')
                    ->size('sm')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('slug_path')
                    ->label('Full Path')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Path copied!')
                    ->size('sm')
                    ->color('gray')
                    ->icon('heroicon-o-globe-alt')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-calendar')
                    ->iconColor('success')
                    ->since()
                    ->tooltip(fn($record) => $record->published_at?->format('F j, Y \a\t g:i A')),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-eye')
                    ->iconColor('primary')
                    ->formatStateUsing(fn($state) => number_format($state ?: 0))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('updated_by')
                    ->label('Last Updated By')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-pencil-square')
                    ->iconColor('warning')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->created_at->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->updated_at->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('breaking_at')
                    ->label('Breaking Since')
                    ->dateTime('M j, Y - H:i')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-bolt')
                    ->iconColor('danger')
                    ->tooltip(fn($record) => $record->breaking_at?->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('breaking_expires')
                    ->label('Breaking Expires')
                    ->dateTime('M j, Y - H:i')
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconColor('warning')
                    ->tooltip(fn($record) => $record->breaking_expires?->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('editor_pick_at')
                    ->label('Featured Since')
                    ->dateTime('M j, Y - H:i')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-star')
                    ->iconColor('success')
                    ->tooltip(fn($record) => $record->editor_pick_at?->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('editor_pick_expires')
                    ->label('Featured Expires')
                    ->dateTime('M j, Y - H:i')
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconColor('warning')
                    ->tooltip(fn($record) => $record->editor_pick_expires?->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->iconColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                TrashedFilter::make()
                    ->label('Archived Posts'),

                SelectFilter::make('category')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('author')
                    ->label('Author')
                    ->relationship('author.user', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('is_breaking')
                    ->label('Breaking News Only')
                    ->query(fn(Builder $query): Builder => $query->where('is_breaking', true))
                    ->toggle(),

                Filter::make('is_editor_pick')
                    ->label("Editor's Picks Only")
                    ->query(fn(Builder $query): Builder => $query->where('is_editor_pick', true))
                    ->toggle(),

                Filter::make('published_recently')
                    ->label('Published This Month')
                    ->query(fn(Builder $query): Builder => $query->where('published_at', '>=', now()->startOfMonth()))
                    ->toggle(),
            ])
            ->filtersFormColumns(2)
            ->toolbarActions([
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete Posts'),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Post'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Move to Trash')
                        ->icon('heroicon-o-trash'),

                    ForceDeleteBulkAction::make()
                        ->label('Delete Permanently')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger'),

                    RestoreBulkAction::make()
                        ->label('Restore')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success'),
                ])
                    ->label('Bulk Actions'),
            ])
            ->emptyStateHeading('No Posts Found')
            ->emptyStateDescription('Create your first post to get started with content management.')
            ->emptyStateIcon('heroicon-o-document-plus')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->extremePaginationLinks()
            ->poll('30s');
    }
}
