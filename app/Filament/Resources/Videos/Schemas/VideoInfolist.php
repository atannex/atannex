<?php

namespace App\Filament\Resources\Videos\Schemas;

use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Infolists\Components\VideoPlayer;

/**
 * Professional Video Infolist Schema
 *
 * Provides a comprehensive, well-organized display of video information
 * with enhanced user experience and visual hierarchy.
 *
 * @package App\Filament\Resources\Videos\Schemas
 * @version 2.0.0
 */
class VideoInfolist
{
    /**
     * Configure the video infolist schema with professional layout
     *
     * @param Schema $schema The schema instance to configure
     * @return Schema The configured schema with all components
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->schema([
                        self::buildHeaderSection(),
                        self::buildVideoPlayerSection(),
                        self::buildContentSection(),
                    ])
                    ->columnSpan(['lg' => 2]),
                Group::make()
                    ->schema([
                        self::buildStatusSection(),
                        self::buildMediaSection(),
                        self::buildClassificationSection(),
                        self::buildMetadataSection(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }

    /**
     * Build the header section with title and key information
     *
     * @return Section
     */
    private static function buildHeaderSection(): Section
    {
        return Section::make()
            ->schema([
                TextEntry::make('title')
                    ->label('Video Title')
                    ->size(TextSize::Large)
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-film')
                    ->iconColor('primary')
                    ->copyable()
                    ->copyMessage('Title copied!')
                    ->copyMessageDuration(1500)
                    ->placeholder('Untitled Video')
                    ->columnSpanFull(),
            ])
            ->heading('Video Overview')
            ->description('Essential video information at a glance')
            ->icon('heroicon-o-video-camera')
            ->iconColor('primary')
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(false);
    }

    /**
     * Build the video player section
     *
     * @return Section
     */
    private static function buildVideoPlayerSection(): Section
    {
        return Section::make()
            ->schema([
                VideoPlayer::make('video_url')
                    ->label('')
                    ->disk('public')
                    ->directory('videos')
                    ->visibility('public')
                    ->columnSpanFull(),
            ])
            ->heading('Video Player')
            ->description('Watch the video directly in the admin panel')
            ->icon('heroicon-o-play-circle')
            ->iconColor('success')
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(false)
            ->aside();
    }

    /**
     * Build the content section with description
     *
     * @return Section
     */
    private static function buildContentSection(): Section
    {
        return Section::make()
            ->schema([
                TextEntry::make('description')
                    ->label('Description')
                    ->markdown()
                    ->placeholder('No description provided')
                    ->prose()
                    ->copyable()
                    ->copyMessage('Description copied!')
                    ->columnSpanFull(),
            ])
            ->heading('Description')
            ->description('Detailed information about the video content')
            ->icon('heroicon-o-document-text')
            ->iconColor('info')
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(false);
    }

    /**
     * Build the status section
     *
     * @return Section
     */
    private static function buildStatusSection(): Section
    {
        return Section::make()
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextEntry::make('flag')
                            ->label('Publication Status')
                            ->badge()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color(fn(string $state): string => match (strtolower($state)) {
                                'published' => 'success',
                                'draft' => 'warning',
                                'scheduled' => 'info',
                                'archived' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn(string $state): string => match (strtolower($state)) {
                                'published' => 'heroicon-m-check-circle',
                                'draft' => 'heroicon-m-pencil-square',
                                'scheduled' => 'heroicon-m-clock',
                                'archived' => 'heroicon-m-archive-box',
                                default => 'heroicon-m-question-mark-circle',
                            })
                            ->formatStateUsing(fn(string $state): string => ucfirst(strtolower($state))),

                        TextEntry::make('published_at')
                            ->label('Publication Date')
                            ->dateTime('F j, Y - h:i A')
                            ->placeholder('Not scheduled')
                            ->icon('heroicon-m-calendar-days')
                            ->iconColor('primary')
                            ->copyable()
                            ->copyMessage('Date copied!')
                            ->visible(fn(?string $state): bool => $state !== null),
                    ]),
            ])
            ->heading('Publishing')
            ->description('Video visibility and scheduling')
            ->icon('heroicon-o-rocket-launch')
            ->iconColor('warning')
            ->compact()
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(false);
    }

    /**
     * Build the media section with thumbnail
     *
     * @return Section
     */
    private static function buildMediaSection(): Section
    {
        return Section::make()
            ->schema([
                ImageEntry::make('image')
                    ->label('')
                    ->disk('public')
                    ->visibility('public')
                    ->imageHeight(200)
                    ->imageWidth('100%')
                    ->extraImgAttributes([
                        'class' => 'rounded-lg object-cover shadow-md',
                        'loading' => 'lazy',
                    ])
                    ->placeholder('No thumbnail uploaded')
                    ->columnSpanFull(),
            ])
            ->heading('Thumbnail')
            ->description('Video preview image')
            ->icon('heroicon-o-photo')
            ->iconColor('success')
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(false)
            ->compact();
    }

    /**
     * Build the classification section
     *
     * @return Section
     */
    private static function buildClassificationSection(): Section
    {
        return Section::make()
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextEntry::make('post.title')
                            ->label('Associated Post')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-m-document-text')
                            ->placeholder('No post linked')
                            ->weight(FontWeight::Medium)
                            ->openUrlInNewTab()
                            ->tooltip('Click to view post'),

                        TextEntry::make('tags')
                            ->label('Tags')
                            ->badge()
                            ->separator(',')
                            ->color('primary')
                            ->icon('heroicon-m-tag')
                            ->placeholder('No tags')
                            ->visible(fn(?array $state): bool => !empty($state)),
                    ]),
            ])
            ->heading('Classification')
            ->description('Organizational links and tags')
            ->icon('heroicon-o-tag')
            ->iconColor('info')
            ->compact()
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(false);
    }

    /**
     * Build the metadata section
     *
     * @return Section
     */
    private static function buildMetadataSection(): Section
    {
        return Section::make()
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextEntry::make('duration')
                            ->label('Duration')
                            ->placeholder('Not specified')
                            ->icon('heroicon-m-clock')
                            ->iconColor('gray')
                            ->badge()
                            ->color('gray')
                            ->formatStateUsing(fn(?string $state): string => $state ?? 'N/A'),

                        TextEntry::make('file_size')
                            ->label('File Size')
                            ->placeholder('Calculating...')
                            ->icon('heroicon-m-server')
                            ->iconColor('gray')
                            ->badge()
                            ->color('gray')
                            ->formatStateUsing(fn(?int $state): string =>
                                $state ? self::formatFileSize($state) : 'N/A'
                            ),

                        TextEntry::make('views_count')
                            ->label('Total Views')
                            ->numeric()
                            ->default(0)
                            ->icon('heroicon-m-eye')
                            ->iconColor('gray')
                            ->badge()
                            ->color('success')
                            ->formatStateUsing(fn(?int $state): string =>
                                self::formatNumber($state ?? 0) . ' views'
                            ),

                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime('M j, Y')
                            ->icon('heroicon-m-plus-circle')
                            ->iconColor('gray')
                            ->since()
                            ->tooltip(fn($record): string => $record->created_at?->format('F j, Y - h:i:s A') ?? 'N/A'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('M j, Y')
                            ->icon('heroicon-m-arrow-path')
                            ->iconColor('gray')
                            ->since()
                            ->tooltip(fn($record): string => $record->updated_at?->format('F j, Y - h:i:s A') ?? 'N/A'),
                    ]),
            ])
            ->heading('Metadata')
            ->description('Additional video statistics and information')
            ->icon('heroicon-o-information-circle')
            ->iconColor('gray')
            ->compact()
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(true);
    }

    /**
     * Format duration from seconds to human-readable time
     *
     * @param int $seconds Duration in seconds
     * @return string Formatted duration (HH:MM:SS or MM:SS)
     */
    private static function formatDuration(int $seconds): string
    {
        if ($seconds < 0) {
            return '00:00';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }

        return sprintf('%02d:%02d', $minutes, $secs);
    }

    /**
     * Format file size in bytes to human-readable format
     *
     * @param int $bytes File size in bytes
     * @return string Formatted file size
     */
    private static function formatFileSize(int $bytes): string
    {
        if ($bytes < 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;
        $size = $bytes;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Format large numbers with suffixes (K, M, B)
     *
     * @param int $number The number to format
     * @return string Formatted number with suffix
     */
    private static function formatNumber(int $number): string
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, 1) . 'B';
        }
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }
        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return (string) $number;
    }
}
