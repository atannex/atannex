<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Video Form Schema Configuration
 *
 * Defines the structure and validation rules for video resource forms.
 * Organizes form fields into logical sections for improved user experience.
 */
class VideoForm
{
    /**
     * Configure the video form schema
     *
     * Creates a comprehensive form layout with sections for video information,
     * media files, publishing settings, classification, and metadata.
     *
     * @param  Schema  $schema  The schema instance to configure
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
     */
    private static function getVideoInformationSection(): Section
    {
        return Section::make('Video Information')
            ->description('Enter the basic details about the video')
            ->icon('heroicon-o-information-circle')
            ->iconColor('primary')
            ->collapsible()
            ->persistCollapsed()
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Video Title')
                            ->maxLength(255)
                            ->placeholder('e.g., Introduction to Laravel Filament')
                            ->columnSpan(2)
                            ->autofocus()
                            ->live(onBlur: true)
                            ->prefixIcon('heroicon-o-film')
                            ->prefixIconColor('primary')
                            ->helperText('Choose a clear, descriptive title that captures the video content'),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(5)
                            ->placeholder('Provide a detailed description of the video content, key topics covered, and what viewers will learn...')
                            ->columnSpan(2)
                            ->autosize()
                            ->helperText('A detailed description helps viewers understand what to expect')
                            ->extraAttributes(['class' => 'resize-none']),
                    ]),
            ])
            ->footerActions([
                // Optional: Add footer actions if needed
            ]);
    }

    /**
     * Get the media files section
     */
    private static function getMediaFilesSection(): Section
    {
        return Section::make('Media Files')
            ->description('Upload the video file and an optional thumbnail image')
            ->icon('heroicon-o-photo')
            ->iconColor('success')
            ->collapsible()
            ->persistCollapsed()
            ->schema([
                Grid::make(2)
                    ->schema([
                        FileUpload::make('video_url')
                            ->label('Video File')
                            ->required()
                            ->disk('public')
                            ->directory('videos')
                            ->visibility('public')
                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'])
                            ->maxSize(512000) // 500MB
                            ->helperText('Maximum file size: 500MB • Supported formats: MP4, MOV, AVI, WebM')
                            ->columnSpan(2)
                            ->imagePreviewHeight('200')
                            ->downloadable()
                            ->openable()
                            ->deletable()
                            ->reorderable()
                            ->appendFiles()
                            ->previewable()
                            ->uploadingMessage('Uploading video...')
                            ->uploadProgressIndicatorPosition('left')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->panelAspectRatio('16:9')
                            ->panelLayout('integrated')
                            ->imageEditorAspectRatioOptions([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->afterStateUpdated(function ($state, $record) {
                                if ($record && $record->video_url && $record->video_url !== $state) {
                                    Storage::disk('public')->delete($record->video_url);
                                }
                            }),

                        FileUpload::make('image')
                            ->label('Thumbnail Image')
                            ->image()
                            ->disk('public')
                            ->directory('videos/thumbnails')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatioOptions([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->imageEditorMode(2)
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1280')
                            ->automaticallyResizeImagesToHeight('720')
                            ->maxSize(5120) // 5MB
                            ->helperText('Recommended size: 1280x720px (16:9) • Maximum file size: 5MB')
                            ->columnSpan(2)
                            ->imagePreviewHeight('200')
                            ->downloadable()
                            ->openable()
                            ->deletable()
                            ->previewable()
                            ->uploadingMessage('Uploading thumbnail...')
                            ->panelAspectRatio('16:9')
                            ->panelLayout('integrated')
                            ->uploadProgressIndicatorPosition('left')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->afterStateUpdated(function ($state, $record) {
                                if ($record && $record->image && $record->image !== $state) {
                                    Storage::disk('public')->delete($record->image);
                                }
                            }),
                    ]),
            ])
            ->aside();
    }

    /**
     * Get the sidebar group containing publishing, classification, and metadata
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
     */
    private static function getPublishingSection(): Section
    {
        return Section::make('Publishing')
            ->description('Control video visibility and scheduling')
            ->icon('heroicon-o-rocket-launch')
            ->iconColor('warning')
            ->collapsible()
            ->persistCollapsed()
            ->compact()
            ->schema([
                Select::make('flag')
                    ->label('Publication Status')
                    ->options(Flag::asSelectArray())
                    ->required()
                    ->default(Flag::DRAFT)
                    ->native(false)
                    ->prefixIcon('heroicon-o-flag')
                    ->selectablePlaceholder(false)
                    ->helperText('Set the current publication status of the video')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Auto-set published_at when status changes to published
                        if ($state === Flag::PUBLISHED) {
                            $set('published_at', now());
                        }
                    }),

                DateTimePicker::make('published_at')
                    ->label('Publication Date')
                    ->native(false)
                    ->prefixIcon('heroicon-o-calendar-days')
                    ->prefixIconColor('primary')
                    ->displayFormat('M d, Y h:i A')
                    ->seconds(false)
                    ->timezone('UTC')
                    ->helperText('Schedule when this video should be published')
                    ->live()
                    ->default(now())
                    ->minDate(now()->subDay())
                    ->maxDate(now()->addYear()),
            ]);
    }

    /**
     * Get the classification section
     */
    private static function getClassificationSection(): Section
    {
        return Section::make('Classification')
            ->description('Organize the video by post')
            ->icon('heroicon-o-tag')
            ->iconColor('info')
            ->collapsible()
            ->persistCollapsed()
            ->compact()
            ->schema([
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->label('Associated Post')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false)
                    ->prefixIcon('heroicon-o-document-text')
                    ->prefixIconColor('info')
                    ->placeholder('Select a related post')
                    ->helperText('Link this video to a blog post or article')
                    ->createOptionForm([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->editOptionForm([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->title)
                    ->live(),
            ]);
    }

    /**
     * Get the metadata section
     */
    private static function getMetadataSection(): Section
    {
        return Section::make('Metadata')
            ->description('Additional information about the video')
            ->icon('heroicon-o-information-circle')
            ->iconColor('gray')
            ->collapsible()
            ->collapsed()
            ->persistCollapsed()
            ->compact()
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('duration')
                            ->label('Duration')
                            ->placeholder('e.g., 10:30')
                            ->helperText('Video duration in MM:SS format')
                            ->prefixIcon('heroicon-o-clock')
                            ->prefixIconColor('gray'),

                        TextInput::make('file_size')
                            ->label('File Size')
                            ->placeholder('Auto-calculated')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Automatically calculated on upload')
                            ->prefixIcon('heroicon-o-server')
                            ->prefixIconColor('gray'),

                        TextInput::make('views_count')
                            ->label('Views')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Total number of views')
                            ->prefixIcon('heroicon-o-eye')
                            ->prefixIconColor('gray'),
                    ]),
            ]);
    }
}
