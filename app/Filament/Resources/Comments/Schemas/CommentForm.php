<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Related post
                Select::make('post_id')
                    ->label('Post')
                    ->relationship('post', 'title')
                    ->required(),

                // Parent comment
                Select::make('parent_id')
                    ->label('Parent Comment')
                    ->relationship('parent', 'comment')
                    ->placeholder('No parent (top-level comment)')
                    ->default(null),

                // Main comment text
                Textarea::make('comment')
                    ->label('Comment')
                    ->required()
                    ->columnSpanFull(),

                // Status
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),

                // Author
                Select::make('user_id')
                    ->label('Author')
                    ->relationship('user', 'name')
                    ->required(),

                // IP information
                TextInput::make('ip_address')
                    ->label('IP Address')
                    ->default(null),

                TextInput::make('ip_country')
                    ->label('IP Country')
                    ->default(null),

                // User agent
                Textarea::make('user_agent')
                    ->label('User Agent')
                    ->default(null)
                    ->columnSpanFull(),

                // Counts
                TextInput::make('likes_count')
                    ->label('Likes')
                    ->numeric()
                    ->default(0),

                TextInput::make('dislikes_count')
                    ->label('Dislikes')
                    ->numeric()
                    ->default(0),

                TextInput::make('replies_count')
                    ->label('Replies')
                    ->numeric()
                    ->default(0),

                // Edited info
                DateTimePicker::make('edited_at')
                    ->label('Edited At'),

                TextInput::make('edited_reason')
                    ->label('Edited Reason')
                    ->default(null),

                Select::make('edited_by')
                    ->label('Edited By')
                    ->relationship('editedBy', 'name')
                    ->default(null),

                Select::make('deleted_by')
                    ->label('Deleted By')
                    ->relationship('deletedBy', 'name')
                    ->default(null),

                // Review info
                DateTimePicker::make('reviewed_at')
                    ->label('Reviewed At'),

                Select::make('reviewed_by')
                    ->label('Reviewed By')
                    ->relationship('reviewedBy', 'name')
                    ->default(null),

                // Moderation notes
                Textarea::make('moderation_notes')
                    ->label('Moderation Notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
