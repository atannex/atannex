<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                    ->label('Featured Image')
                    ->disk('public')
                    ->imageHeight(50)
                    ->width(80)
                    ->extraImgAttributes(['class' => 'rounded-lg'])
                    ->defaultImageUrl(url('/images/placeholder-post.jpg'))
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

                TextColumn::make('flag')
                    ->badge()
                    ->label('Status')
                    ->colors([
                        'danger' => 'draft',
                        'warning' => 'pending',
                        'success' => 'published',
                        'secondary' => 'archived',
                    ])
                    ->icons([
                        'heroicon-o-document-text' => 'draft',
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'published',
                        'heroicon-o-archive-box' => 'archived',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('author.user.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-user')
                    ->iconColor('gray'),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copied!')
                    ->size('sm')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable()
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
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_by')
                    ->label('Last Updated By')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-pencil-square')
                    ->iconColor('warning')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->created_at->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn($record) => $record->updated_at->format('F j, Y \a\t g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->iconColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                TrashedFilter::make()
                    ->label('Archived Posts'),

                SelectFilter::make('flag')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending Review',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->multiple(),

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

                Filter::make('published_recently')
                    ->label('Published This Month')
                    ->query(fn(Builder $query): Builder => $query->where('published_at', '>=', now()->startOfMonth()))
                    ->toggle(),
            ])
            ->filtersFormColumns(3)
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
