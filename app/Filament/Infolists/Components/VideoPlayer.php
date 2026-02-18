<?php

namespace App\Filament\Infolists\Components;

use Closure;
use Filament\Infolists\Components\Entry;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

/**
 * VideoPlayer Infolist Component
 *
 * A professional video player component for Filament infolists with support
 * for multiple video sources, custom controls, and responsive design.
 *
 * @version 2.0.0
 */
class VideoPlayer extends Entry
{
    /**
     * The Blade view for rendering the video player
     */
    protected string $view = 'filament.infolists.components.video-player';

    /**
     * Storage disk for video files
     */
    protected string|Closure|null $disk = null;

    /**
     * Directory path for video files
     */
    protected string|Closure|null $directory = null;

    /**
     * File visibility (public/private)
     */
    protected string|Closure|null $visibility = 'public';

    /**
     * Video width (CSS value)
     */
    protected string|Closure|null $width = '100%';

    /**
     * Video height (CSS value)
     */
    protected string|Closure|null $height = 'auto';

    /**
     * Maximum height for the video player
     */
    protected string|Closure|null $maxHeight = '600px';

    /**
     * Whether to show video controls
     */
    protected bool|Closure $controls = true;

    /**
     * Whether to autoplay the video
     */
    protected bool|Closure $autoplay = false;

    /**
     * Whether to loop the video
     */
    protected bool|Closure $loop = false;

    /**
     * Whether to mute the video by default
     */
    protected bool|Closure $muted = false;

    /**
     * Video poster image URL
     */
    protected string|Closure|null $poster = null;

    /**
     * Preload strategy (none, metadata, auto)
     */
    protected string|Closure $preload = 'metadata';

    /**
     * Whether to allow fullscreen
     */
    protected bool|Closure $allowFullscreen = true;

    /**
     * Whether to allow picture-in-picture
     */
    protected bool|Closure $allowPictureInPicture = true;

    /**
     * Additional HTML attributes for the video element
     */
    protected array|Closure $videoAttributes = [];

    /**
     * Custom CSS classes for the container
     */
    protected string|Closure|null $containerClass = null;

    /**
     * Whether to show a download button
     */
    protected bool|Closure $downloadable = false;

    /**
     * Playback speed options
     */
    protected array|Closure $playbackRates = [0.5, 0.75, 1, 1.25, 1.5, 2];

    /**
     * Default playback rate
     */
    protected float|Closure $defaultPlaybackRate = 1.0;

    /**
     * Set the storage disk for video files
     */
    public function disk(string|Closure|null $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Set the directory path for video files
     */
    public function directory(string|Closure|null $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Set the file visibility
     */
    public function visibility(string|Closure|null $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Set the video width
     */
    public function width(string|Closure|null $width): static
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the video height
     */
    public function height(string|Closure|null $height): static
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Set the maximum height
     */
    public function maxHeight(string|Closure|null $maxHeight): static
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    /**
     * Enable or disable video controls
     */
    public function controls(bool|Closure $controls = true): static
    {
        $this->controls = $controls;

        return $this;
    }

    /**
     * Enable or disable autoplay
     */
    public function autoplay(bool|Closure $autoplay = true): static
    {
        $this->autoplay = $autoplay;

        return $this;
    }

    /**
     * Enable or disable video looping
     */
    public function loop(bool|Closure $loop = true): static
    {
        $this->loop = $loop;

        return $this;
    }

    /**
     * Mute the video by default
     */
    public function muted(bool|Closure $muted = true): static
    {
        $this->muted = $muted;

        return $this;
    }

    /**
     * Set the poster image URL
     */
    public function poster(string|Closure|null $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Set the preload strategy
     */
    public function preload(string|Closure $preload): static
    {
        $this->preload = $preload;

        return $this;
    }

    /**
     * Enable or disable fullscreen
     */
    public function allowFullscreen(bool|Closure $allowFullscreen = true): static
    {
        $this->allowFullscreen = $allowFullscreen;

        return $this;
    }

    /**
     * Enable or disable picture-in-picture
     */
    public function allowPictureInPicture(bool|Closure $allowPictureInPicture = true): static
    {
        $this->allowPictureInPicture = $allowPictureInPicture;

        return $this;
    }

    /**
     * Set additional HTML attributes for the video element
     */
    public function videoAttributes(array|Closure $attributes): static
    {
        $this->videoAttributes = $attributes;

        return $this;
    }

    /**
     * Set custom CSS classes for the container
     */
    public function containerClass(string|Closure|null $class): static
    {
        $this->containerClass = $class;

        return $this;
    }

    /**
     * Enable or disable download button
     */
    public function downloadable(bool|Closure $downloadable = true): static
    {
        $this->downloadable = $downloadable;

        return $this;
    }

    /**
     * Set available playback rates
     */
    public function playbackRates(array|Closure $rates): static
    {
        $this->playbackRates = $rates;

        return $this;
    }

    /**
     * Set default playback rate
     */
    public function defaultPlaybackRate(float|Closure $rate): static
    {
        $this->defaultPlaybackRate = $rate;

        return $this;
    }

    /**
     * Get the storage disk
     */
    public function getDisk(): ?string
    {
        return $this->evaluate($this->disk);
    }

    /**
     * Get the directory path
     */
    public function getDirectory(): ?string
    {
        return $this->evaluate($this->directory);
    }

    /**
     * Get the file visibility
     */
    public function getVisibility(): ?string
    {
        return $this->evaluate($this->visibility);
    }

    /**
     * Get the video width
     */
    public function getWidth(): ?string
    {
        return $this->evaluate($this->width);
    }

    /**
     * Get the video height
     */
    public function getHeight(): ?string
    {
        return $this->evaluate($this->height);
    }

    /**
     * Get the maximum height
     */
    public function getMaxHeight(): ?string
    {
        return $this->evaluate($this->maxHeight);
    }

    /**
     * Check if controls are enabled
     */
    public function hasControls(): bool
    {
        return $this->evaluate($this->controls);
    }

    /**
     * Check if autoplay is enabled
     */
    public function isAutoplay(): bool
    {
        return $this->evaluate($this->autoplay);
    }

    /**
     * Check if loop is enabled
     */
    public function isLoop(): bool
    {
        return $this->evaluate($this->loop);
    }

    /**
     * Check if video is muted
     */
    public function isMuted(): bool
    {
        return $this->evaluate($this->muted);
    }

    /**
     * Get the poster image URL
     */
    public function getPoster(): ?string
    {
        return $this->evaluate($this->poster);
    }

    /**
     * Get the preload strategy
     */
    public function getPreload(): string|Closure
    {
        return $this->evaluate($this->preload);
    }

    /**
     * Check if fullscreen is allowed
     */
    public function isFullscreenAllowed(): bool
    {
        return $this->evaluate($this->allowFullscreen);
    }

    /**
     * Check if picture-in-picture is allowed
     */
    public function isPictureInPictureAllowed(): bool
    {
        return $this->evaluate($this->allowPictureInPicture);
    }

    /**
     * Get video attributes
     */
    public function getVideoAttributes(): array|Closure
    {
        return $this->evaluate($this->videoAttributes);
    }

    /**
     * Get container CSS class
     */
    public function getContainerClass(): ?string
    {
        return $this->evaluate($this->containerClass);
    }

    /**
     * Check if download is enabled
     */
    public function isDownloadable(): bool
    {
        return $this->evaluate($this->downloadable);
    }

    /**
     * Get playback rates
     */
    public function getPlaybackRates(): array|Closure
    {
        return $this->evaluate($this->playbackRates);
    }

    /**
     * Get default playback rate
     */
    public function getDefaultPlaybackRate(): float
    {
        return $this->evaluate($this->defaultPlaybackRate);
    }

    /**
     * Get the video URL with proper storage handling
     */
    public function getVideoUrl(): ?string
    {
        $state = $this->getState();

        if (empty($state)) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($state, FILTER_VALIDATE_URL)) {
            return $state;
        }

        $disk = $this->getDisk();
        if ($disk) {
            $storage = Storage::disk($disk);

            // Make sure the disk supports URLs
            if ($storage instanceof Filesystem && method_exists($storage, 'url')) {
                return $storage->url($state);
            }

            // For disks that don't support url(), fallback
            return asset("storage/{$state}");
        }

        return $state;
    }

    /**
     * Get the video MIME type
     */
    public function getVideoMimeType(): string
    {
        $url = $this->getVideoUrl();

        if (! $url) {
            return 'video/mp4';
        }

        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        return match ($extension) {
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'ogg', 'ogv' => 'video/ogg',
            'mov' => 'video/quicktime',
            'avi' => 'video/x-msvideo',
            'wmv' => 'video/x-ms-wmv',
            'flv' => 'video/x-flv',
            'm4v' => 'video/x-m4v',
            default => 'video/mp4',
        };
    }
}
