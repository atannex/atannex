<?php

namespace App\Filament\Resources\VideoModules\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VideoModuleForm
{
    /**
     * Configure the schema for the VideoModule form.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('content')
                    ->label('Content')
                    ->default(null)
                    ->columnSpanFull(),

                Repeater::make('images')
                    ->label('Images')
                    ->schema([
                        FileUpload::make('file')
                            ->label('Upload Image')
                            ->default(null)
                            ->columnSpanFull(),

                        TextInput::make('caption')
                            ->label('Caption')
                            ->columnSpanFull(),

                        TextInput::make('alt')
                            ->label('Alt Text')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->minItems(1)
                    ->maxItems(10)
                    ->orderColumn(),

                Select::make('video_id')
                    ->label('Associated Video')
                    ->relationship('video', 'title')
                    ->preload()
                    ->searchable()
                    ->required(),
            ]);
    }
}
