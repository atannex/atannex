<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Enums\Flag;
use App\Enums\Image;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Filament\Traits\HasEnumColumnAndField;

class GalleryForm
{
    use HasEnumColumnAndField;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('original_name')
                    ->default(null),
                self::makeEnumField('type', Image::class, default: Image::LOGO)
                    ->required()
                    ->label('Choose Your Image Type'),
                FileUpload::make('image')
                    ->label('Featured Image')
                    ->disk('public')
                    ->visibility('public')
                    ->directory('icons')
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
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                self::makeEnumField('flag', Flag::class, default: Flag::PENDING)
                    ->label('Status Flag')
                    ->helperText('Current status of this account')
            ]);
    }
}
