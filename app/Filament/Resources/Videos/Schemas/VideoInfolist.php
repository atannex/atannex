<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Models\Posts\Video;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
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
                // Main Content Area (2 columns)
                Group::make()
                    ->schema([
                        self::buildHeaderSection(),
                        self::buildVideoPlayerSection(),
                        self::buildContentSection(),
                        self::buildMediaSection(),
                    ])
                    ->columnSpan(['lg' => 2]),

                // Sidebar (1 column)
                Group::make()
                    ->schema([
                        self::buildStatusSection(),
                        self::buildClassificationSection(),
                        self::buildMetricsSection(),
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
                    ->label('Title')
                    ->size(TextSize::Large)
                    ->weight(FontWeight::Bold)
                    ->getStateUsing(fn($record) => $record->title . ($record->slug ? " ({$record->slug})" : ''))
                    ->columnSpanFull(),

                Grid::make(3)
                    ->schema([
                        TextEntry::make('duration')
                            ->label('Duration')
                            ->icon('heroicon-m-clock')
                            ->formatStateUsing(
                                fn(?string $state): string =>
                                $state ? self::formatDuration((int) $state) : 'N/A'
                            )
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('views_count')
                            ->label('Views')
                            ->icon('heroicon-m-eye')
                            ->formatStateUsing(
                                fn(?int $state): string =>
                                number_format($state ?? 0)
                            )
                            ->badge()
                            ->color('info'),

                        TextEntry::make('published_at')
                            ->label('Published')
                            ->icon('heroicon-m-calendar')
                            ->date('M j, Y')
                            ->badge()
                            ->color('success')
                            ->placeholder('Unpublished'),
                    ]),
            ])
            ->heading('Video Overview')
            ->icon('heroicon-o-video-camera')
            ->iconColor('primary');
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
                    ->directory('video/url')
                    ->visibility('public')
                    ->columnSpanFull(),
            ])
            ->heading('Video Player')
            ->icon('heroicon-o-play-circle')
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(false);
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
                    ->label('')
                    ->markdown()
                    ->placeholder('No description provided')
                    ->columnSpanFull(),
            ])
            ->heading('Description')
            ->icon('heroicon-o-document-text')
            ->collapsible()
            ->persistCollapsed();
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
                    ->imageHeight(300)
                    ->imageWidth('100%')
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover'])
                    ->columnSpanFull(),
            ])
            ->heading('Thumbnail')
            ->icon('heroicon-o-photo')
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(true);
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
                TextEntry::make('flag')
                    ->label('Publication Status')
                    ->badge()
                    ->size(TextSize::Medium)
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
                    ->label('Publish Date')
                    ->dateTime('F j, Y')
                    ->placeholder('Not published')
                    ->icon('heroicon-m-calendar-days')
                    ->visible(fn(?string $state): bool => $state !== null),

                TextEntry::make('scheduled_at')
                    ->label('Scheduled For')
                    ->dateTime('F j, Y \a\t g:i A')
                    ->placeholder('Not scheduled')
                    ->icon('heroicon-m-clock')
                    ->color('info')
                    ->visible(fn(?string $state): bool => $state !== null),
            ])
            ->heading('Status')
            ->icon('heroicon-o-signal')
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
                TextEntry::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-tag')
                    ->placeholder('Uncategorized')
                    ->default('Uncategorized'),

                TextEntry::make('author.user.name')
                    ->label('Author')
                    ->icon('heroicon-m-user')
                    ->color('gray')
                    ->placeholder('Unknown')
                    ->openUrlInNewTab(),

                TextEntry::make('region.name')
                    ->label('Region')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-globe-americas')
                    ->placeholder('Global'),

                TextEntry::make('tags')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->color('info')
                    ->placeholder('No tags')
                    ->visible(fn(?array $state): bool => !empty($state)),
            ])
            ->heading('Classification')
            ->icon('heroicon-o-folder-open')
            ->compact();
    }

    /**
     * Build the metrics section
     *
     * @return Section
     */
    private static function buildMetricsSection(): Section
    {
        return Section::make()
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextEntry::make('likes_count')
                            ->label('Likes')
                            ->icon('heroicon-m-heart')
                            ->color('danger')
                            ->formatStateUsing(
                                fn(?int $state): string =>
                                number_format($state ?? 0)
                            ),

                        TextEntry::make('comments_count')
                            ->label('Comments')
                            ->icon('heroicon-m-chat-bubble-left')
                            ->color('info')
                            ->formatStateUsing(
                                fn(?int $state): string =>
                                number_format($state ?? 0)
                            ),

                        TextEntry::make('shares_count')
                            ->label('Shares')
                            ->icon('heroicon-m-share')
                            ->color('success')
                            ->formatStateUsing(
                                fn(?int $state): string =>
                                number_format($state ?? 0)
                            ),

                        TextEntry::make('downloads_count')
                            ->label('Downloads')
                            ->icon('heroicon-m-arrow-down-tray')
                            ->color('warning')
                            ->formatStateUsing(
                                fn(?int $state): string =>
                                number_format($state ?? 0)
                            ),
                    ]),
            ])
            ->heading('Engagement')
            ->icon('heroicon-o-chart-bar')
            ->collapsible()
            ->persistCollapsed()
            ->compact();
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
                TextEntry::make('id')
                    ->label('Video ID')
                    ->icon('heroicon-m-hashtag')
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('ID copied!')
                    ->copyMessageDuration(1500),

                TextEntry::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->icon('heroicon-m-plus-circle')
                    ->color('success'),

                TextEntry::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y g:i A')
                    ->icon('heroicon-m-pencil')
                    ->color('warning')
                    ->since(),

                TextEntry::make('updated_by')
                    ->label('Updated By')
                    ->icon('heroicon-m-user-circle')
                    ->color('gray')
                    ->formatStateUsing(
                        fn(?int $state): string =>
                        $state ? "User #{$state}" : 'System'
                    )
                    ->visible(fn(?int $state): bool => $state !== null),

                TextEntry::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('M j, Y g:i A')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->visible(fn(Video $record): bool => $record->trashed()),
            ])
            ->heading('System Information')
            ->icon('heroicon-o-information-circle')
            ->collapsible()
            ->persistCollapsed()
            ->collapsed(true)
            ->compact();
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
