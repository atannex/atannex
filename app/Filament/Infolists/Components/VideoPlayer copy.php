<?php

namespace App\Filament\Infolists\Components;

use Closure;
use Filament\Infolists\Components\Entry;

class VideoPlayer extends Entry
{
    protected string $view = 'filament.infolists.components.video-player';

    protected string|Closure|null $width = '100%';
    protected string|Closure|null $height = 'auto';
    protected string|Closure|null $maxHeight = '600px';
    protected bool|Closure $controls = true;
    protected bool|Closure $autoplay = false;
    protected bool|Closure $loop = false;
    protected bool|Closure $muted = false;
    protected string|Closure|null $poster = null;
    protected string|Closure $preload = 'metadata';
    protected bool|Closure $allowFullscreen = true;
    protected string | Closure | null $disk = null;
    protected bool|Closure $allowPictureInPicture = true;
    protected array|Closure $videoAttributes = [];
    protected string|Closure|null $containerClass = null;
    protected array|Closure $playbackRates = [0.5, 0.75, 1, 1.25, 1.5, 2];
    protected float|Closure $defaultPlaybackRate = 1.0;

    public function width(string|Closure|null $width): static
    {
        $this->width = $width;
        return $this;
    }
    public function height(string|Closure|null $height): static
    {
        $this->height = $height;
        return $this;
    }
    public function maxHeight(string|Closure|null $maxHeight): static
    {
        $this->maxHeight = $maxHeight;
        return $this;
    }
    public function controls(bool|Closure $controls = true): static
    {
        $this->controls = $controls;
        return $this;
    }
    public function autoplay(bool|Closure $autoplay = true): static
    {
        $this->autoplay = $autoplay;
        return $this;
    }
    public function loop(bool|Closure $loop = true): static
    {
        $this->loop = $loop;
        return $this;
    }
    public function muted(bool|Closure $muted = true): static
    {
        $this->muted = $muted;
        return $this;
    }
    public function poster(string|Closure|null $poster): static
    {
        $this->poster = $poster;
        return $this;
    }
    public function preload(string|Closure $preload): static
    {
        $this->preload = $preload;
        return $this;
    }
    public function allowFullscreen(bool|Closure $allowFullscreen = true): static
    {
        $this->allowFullscreen = $allowFullscreen;
        return $this;
    }
    public function allowPictureInPicture(bool|Closure $allowPictureInPicture = true): static
    {
        $this->allowPictureInPicture = $allowPictureInPicture;
        return $this;
    }
    public function videoAttributes(array|Closure $attributes): static
    {
        $this->videoAttributes = $attributes;
        return $this;
    }
    public function containerClass(string|Closure|null $class): static
    {
        $this->containerClass = $class;
        return $this;
    }
    public function playbackRates(array|Closure $rates): static
    {
        $this->playbackRates = $rates;
        return $this;
    }
    public function defaultPlaybackRate(float|Closure $rate): static
    {
        $this->defaultPlaybackRate = $rate;
        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->evaluate($this->width);
    }
    public function getHeight(): ?string
    {
        return $this->evaluate($this->height);
    }
    public function getMaxHeight(): ?string
    {
        return $this->evaluate($this->maxHeight);
    }
    public function hasControls(): bool
    {
        return $this->evaluate($this->controls);
    }
    public function isAutoplay(): bool
    {
        return $this->evaluate($this->autoplay);
    }
    public function isLoop(): bool
    {
        return $this->evaluate($this->loop);
    }
    public function isMuted(): bool
    {
        return $this->evaluate($this->muted);
    }
    public function getPoster(): ?string
    {
        return $this->evaluate($this->poster);
    }
    public function getPreload(): string|Closure|null
    {
        return $this->evaluate($this->preload);
    }
    public function isFullscreenAllowed(): bool
    {
        return $this->evaluate($this->allowFullscreen);
    }
    public function isPictureInPictureAllowed(): bool
    {
        return $this->evaluate($this->allowPictureInPicture);
    }
    public function getVideoAttributes(): array|Closure
    {
        return $this->evaluate($this->videoAttributes);
    }
    public function getContainerClass(): ?string
    {
        return $this->evaluate($this->containerClass);
    }
    public function getPlaybackRates(): array|Closure
    {
        return $this->evaluate($this->playbackRates);
    }
    public function getDefaultPlaybackRate(): float
    {
        return $this->evaluate($this->defaultPlaybackRate);
    }

    public function getVideoUrl(): ?string
    {
        $state = $this->getState();
        if (empty($state)) return null;
        if (filter_var($state, FILTER_VALIDATE_URL)) return $state;
        return $state;
    }

    public function getVideoMimeType(): string
    {
        $url = $this->getVideoUrl();
        if (!$url) return 'video/mp4';
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
