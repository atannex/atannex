<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\Select;
use App\Enums\Image;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class GalleryForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('original_name')
                    ->default(null),

                Select::make('type')
                    ->options(Image::asSelectArray())
                    ->label('Choose Your Image Type')
                    ->searchable()
                    ->required()
                    ->preload()
                    ->default(Image::LOGO),

                FileUpload::make('image')
                    ->label('Featured Image')
                    ->disk('public')
                    ->visibility('public')
                    ->directory(fn($record) => $record?->getImageDirectory())
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9' => '16:9 (Recommended)',
                        '4:3' => '4:3 (Standard)',
                        '1:1' => '1:1 (Square)',
                    ])
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Recommended size: 1200x675px (16:9 ratio)')
                    ->imagePreviewHeight('250')
                    ->uploadingMessage('Uploading your image...')
                    ->columnSpanFull(),

                Select::make('flag')
                    ->label('Status Flag')
                    ->helperText('Current status of this account')
                    ->options(Flag::asSelectArray())
                    ->searchable()
                    ->required()
                    ->preload()
                    ->columnSpan(['default' => 12, 'md' => 4, 'lg' => 3])
                    ->default(Flag::PENDING),
            ]);
    }
}
