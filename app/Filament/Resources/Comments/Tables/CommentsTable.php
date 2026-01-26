<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Filament\Traits\HasVisibilityRules;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CommentsTable
{
    use HasVisibilityRules;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                /*
                |--------------------------------------------------------------------------
                | Primary Content
                |--------------------------------------------------------------------------
                */
                TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50)
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('reply_count')
                    ->label('Replies')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Author
                |--------------------------------------------------------------------------
                */
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('is_guest')
                    ->label('Guest')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('guest_name')
                    ->label('Guest Name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                /*
                |--------------------------------------------------------------------------
                | Context
                |--------------------------------------------------------------------------
                */
                TextColumn::make('commentable_type')
                    ->label('Type')
                    ->formatStateUsing(fn($state) => class_basename($state))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('commentable_id')
                    ->label('Model ID')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('parent_id')
                    ->label('Parent')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                /*
                |--------------------------------------------------------------------------
                | Engagement (Moderator Only)
                |--------------------------------------------------------------------------
                */
                TextColumn::make('like_count')
                    ->label('Likes')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('dislike_count')
                    ->label('Dislikes')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->visible(fn() => static::canSeeModerationContent()),

                /*
                |--------------------------------------------------------------------------
                | System & Moderation (Moderator Only)
                |--------------------------------------------------------------------------
                */
                TextColumn::make('edited_at')
                    ->label('Edited')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('comment_hash')
                    ->label('Hash')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => static::canSeeModerationContent()),

                /*
                |--------------------------------------------------------------------------
                | Optional Status Badge
                |--------------------------------------------------------------------------
                */
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'spam',
                        'gray'    => 'hidden',
                    ])
                    ->sortable()
                    ->visible(fn() => static::canSeeModerationContent()),
            ])

            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('is_guest')
                    ->label('Author Type')
                    ->options([
                        0 => 'Registered User',
                        1 => 'Guest',
                    ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | Row Actions
            |--------------------------------------------------------------------------
            */
            ->recordActions([
                EditAction::make(),
            ])

            /*
            |--------------------------------------------------------------------------
            | Bulk Actions
            |--------------------------------------------------------------------------
            */
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
