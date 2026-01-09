<?php

namespace App\Filament\Resources\Documents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),

                // Type as select
                Select::make('type')
                    ->options([
                        'policy' => 'Policy',
                        'procedure' => 'Procedure',
                        'guideline' => 'Guideline',
                        'manual' => 'Manual',
                        'report' => 'Report',
                    ])
                    ->searchable()
                    ->required(),

                TextInput::make('slug')
                    ->required(),

                TextInput::make('slug_path')
                    ->disabled()
                    ->helperText('Automatically generated from type and slug'),

                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),

                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->default(null),

                Select::make('flag')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),

                FileUpload::make('image')
                    ->image()
                    ->directory('documents')
                    ->preserveFilenames()
                    ->deleteUploadedFile('image'),

                DateTimePicker::make('published_at'),
            ]);
    }
}
