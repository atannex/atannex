<?php

namespace App\Filament\Resources\VideoModules\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VideoModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([

                        Section::make('Video Association')
                            ->description('Link this module to a video')
                            ->icon('heroicon-o-film')
                            ->schema([
                                Select::make('video_id')
                                    ->label('Associated Video')
                                    ->relationship('video', 'title')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->placeholder('Select a video')
                                    ->helperText('Choose the video this module belongs to'),
                            ])
                            ->collapsible()
                            ->columns(1),

                        Section::make('Module Content')
                            ->description('Add descriptive content for this video module')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                RichEditor::make('content')
                                    ->label('Content')
                                    ->placeholder('Enter module content...')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'bulletList',
                                        'orderedList',
                                        'link',
                                        'blockquote',
                                        'codeBlock',
                                    ])
                                    ->maxLength(10000)
                                    ->helperText('Provide detailed information about this module (supports formatting)')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->columns(1),

                        Section::make('Module Images')
                            ->description('Upload and manage images for this module')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Repeater::make('images')
                                    ->label('')
                                    ->schema([
                                        FileUpload::make('file')
                                            ->label('Upload Image')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(5120)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->directory('video-modules/images')
                                            ->visibility('public')
                                            ->required()
                                            ->columnSpanFull(),

                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('caption')
                                                    ->label('Caption')
                                                    ->placeholder('Brief description of the image')
                                                    ->maxLength(255)
                                                    ->helperText('Displayed below the image'),

                                                TextInput::make('alt')
                                                    ->label('Alt Text')
                                                    ->placeholder('Descriptive text for accessibility')
                                                    ->maxLength(255)
                                                    ->required()
                                                    ->helperText('Important for SEO and accessibility'),
                                            ])
                                    ])
                                    ->itemLabel(fn(array $state): ?string => $state['caption'] ?? null)
                                    ->collapsed()
                                    ->cloneable()
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Image')
                                    ->deleteAction(
                                        fn($action) => $action->requiresConfirmation()
                                    )
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->collapsed(false)
                            ->columns(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
