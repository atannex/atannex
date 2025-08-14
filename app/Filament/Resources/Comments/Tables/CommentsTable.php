<?php

namespace App\Filament\Resources\Comments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Display related post title
                TextColumn::make('post.title')
                    ->label('Post')
                    ->sortable()
                    ->searchable(),

                // Display parent comment (if exists)
                TextColumn::make('parent.comment')
                    ->label('Parent Comment')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

                // Comment status
                TextColumn::make('status')
                    ->sortable()
                    ->searchable(),

                // Comment author
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                // IP information
                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),

                TextColumn::make('ip_country')
                    ->label('Country')
                    ->searchable(),

                // Counts
                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->sortable(),

                TextColumn::make('dislikes_count')
                    ->label('Dislikes')
                    ->sortable(),

                TextColumn::make('replies_count')
                    ->label('Replies')
                    ->sortable(),

                // Edited information
                TextColumn::make('edited_at')
                    ->label('Edited At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('edited_reason')
                    ->label('Edited Reason')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('editedBy.name')
                    ->label('Edited By')
                    ->sortable()
                    ->searchable(),

                // Deleted and reviewed information
                TextColumn::make('deletedBy.name')
                    ->label('Deleted By')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('reviewed_at')
                    ->label('Reviewed At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('reviewedBy.name')
                    ->label('Reviewed By')
                    ->sortable()
                    ->searchable(),

                // Timestamps
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
