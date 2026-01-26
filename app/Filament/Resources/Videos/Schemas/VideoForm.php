<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Enums\Flag;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

/**
 * Video Form Schema Configuration
 *
 * Defines the structure and validation rules for video resource forms.
 * Organizes form fields into logical sections for improved user experience.
 *
 * @package App\Filament\Resources\Videos\Schemas
 */
class VideoForm
{
    /**
     * Configure the video form schema
     *
     * Creates a comprehensive form layout with sections for video information,
     * media files, publishing settings, classification, and metadata.
     *
     * @param Schema $schema The schema instance to configure
     * @return Schema The configured schema with all form components
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getMainContentGroup(),
                self::getSidebarGroup(),
            ])
            ->columns(3);
    }

    /**
     * Get the main content group containing video information and media files
     *
     * @return Group
     */
    private static function getMainContentGroup(): Group
    {
        return Group::make()
            ->schema([
                self::getVideoInformationSection(),
                self::getMediaFilesSection(),
            ])
            ->columnSpan(['lg' => 2]);
    }

    /**
     * Get the video information section
     *
     * @return Section
     */
    private static function getVideoInformationSection(): Section
    {
        return Section::make('Video Information')
            ->description('Enter the basic details about the video')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter video title')
                            ->columnSpan(2),

                        Textarea::make('description')
                            ->rows(4)
                            ->placeholder('Provide a detailed description of the video content')
                            ->columnSpan(2),

                        TextInput::make('duration')
                            ->numeric()
                            ->suffix('seconds')
                            ->placeholder('Auto-detected from video file')
                            ->columnSpanFull()
                            ->helperText('Duration will be automatically detected upon upload. You may override this value if needed.'),
                    ]),
            ]);
    }

    /**
     * Get the media files section
     *
     * @return Section
     */
    private static function getMediaFilesSection(): Section
    {
        return Section::make('Media Files')
            ->description('Upload the video file and an optional thumbnail image')
            ->schema([
                Grid::make(2)
                    ->schema([
                        FileUpload::make('video_url')
                            ->label('Video File')
                            ->required()
                            ->acceptedFileTypes(['video/*'])
                            ->maxSize(512000)
                            ->helperText('Supported formats: MP4, MOV, AVI, WebM')
                            ->columnSpan(1),

                        FileUpload::make('image')
                            ->label('Thumbnail Image')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('Recommended size: 1280x720px')
                            ->columnSpan(1),
                    ]),
            ]);
    }

    /**
     * Get the sidebar group containing publishing, classification, and metadata
     *
     * @return Group
     */
    private static function getSidebarGroup(): Group
    {
        return Group::make()
            ->schema([
                self::getPublishingSection(),
                self::getClassificationSection(),
                self::getMetadataSection(),
            ])
            ->columnSpan(['lg' => 1]);
    }

    /**
     * Get the publishing section
     *
     * @return Section
     */
    private static function getPublishingSection(): Section
    {
        return Section::make('Publishing')
            ->description('Control video visibility and scheduling')
            ->schema([
                Select::make('flag')
                    ->label('Publication Status')
                    ->options(Flag::asSelectArray())
                    ->required()
                    ->default(Flag::DRAFT)
                    ->native(false)
                    ->helperText('Set the current publication status of the video'),

                DateTimePicker::make('published_at')
                    ->label('Publication Date')
                    ->native(false)
                    ->helperText('Schedule when this video should be published'),
            ]);
    }

    /**
     * Get the classification section
     *
     * @return Section
     */
    private static function getClassificationSection(): Section
    {
        return Section::make('Classification')
            ->description('Organize the video by category, author, and region')
            ->schema([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder('Select a category'),

                Select::make('author_id')
                    ->relationship('author.user', 'name')
                    ->label('Author')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder('Select an author'),

                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->label('Region')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder('Select a region'),
            ]);
    }

    /**
     * Get the metadata section
     *
     * @return Section
     */
    private static function getMetadataSection(): Section
    {
        return Section::make('Metadata')
            ->description('System-managed information')
            ->schema([
                TextInput::make('updated_by')
                    ->label('Last Updated By')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('This field is automatically updated when the video is saved'),
            ])
            ->collapsible()
            ->collapsed();
    }
}
