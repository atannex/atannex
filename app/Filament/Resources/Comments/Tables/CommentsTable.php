<?php

namespace App\Filament\Resources\Comments\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class CommentsTable
{

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
                    ->alignCenter(),

                TextColumn::make('dislike_count')
                    ->label('Dislikes')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                /*
                |--------------------------------------------------------------------------
                | System & Moderation (Moderator Only)
                |--------------------------------------------------------------------------
                */
                TextColumn::make('edited_at')
                    ->label('Edited')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comment_hash')
                    ->label('Hash')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->sortable(),
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
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete Comment'),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Comment'),
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
