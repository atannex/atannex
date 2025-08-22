<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                FileUpload::make('image')
                    ->label('Profile Image')
                    ->disk('public')
                    ->visibility('public')
                    ->directory('profile/images')
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
                DatePicker::make('date_of_birth'),
                TextInput::make('gender')
                    ->default('male'),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('status')
                    ->required()
                    ->default('restricted'),
                DateTimePicker::make('last_login_at'),
                TextInput::make('last_login_ip')
                    ->default(null),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->label('Roles'),
                Select::make('permissions')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->preload()
                    ->label('Permissions'),
            ]);
    }
}
