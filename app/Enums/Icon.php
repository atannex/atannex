<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Icon extends Enum
{
    const FACEBOOK       = 'facebook';
    const TWITTER        = 'twitter';
    const LINKEDIN       = 'linkedin';
    const WHATSAPP       = 'whatsapp';
    const REDDIT         = 'reddit';
    const TELEGRAM       = 'telegram';
    const INSTAGRAM      = 'instagram';
    const PINTEREST      = 'pinterest';
    const YOUTUBE        = 'youtube';
    const TIKTOK         = 'tiktok';
    const SNAPCHAT       = 'snapchat';
    const TUMBLR         = 'tumblr';
    const MEDIUM         = 'medium';
    const VIMEO          = 'vimeo';
    const GITHUB         = 'github';
    const STACKOVERFLOW  = 'stackoverflow';
    const DRIBBBLE       = 'dribbble';
    const FLICKR         = 'flickr';
    const DISCORD        = 'discord';
    const SLACK          = 'slack';
    const EMAIL          = 'email';
    const WEBSITE        = 'website';

    public static array $data = [
        self::FACEBOOK => [
            'label' => 'Facebook',
            'icon'  => 'fab fa-facebook-f',
            'color' => '#1877F2',
        ],
        self::TWITTER => [
            'label' => 'Twitter',
            'icon'  => 'fab fa-twitter',
            'color' => '#1DA1F2',
        ],
        self::LINKEDIN => [
            'label' => 'LinkedIn',
            'icon'  => 'fab fa-linkedin-in',
            'color' => '#0077B5',
        ],
        self::WHATSAPP => [
            'label' => 'WhatsApp',
            'icon'  => 'fab fa-whatsapp',
            'color' => '#25D366',
        ],
        self::REDDIT => [
            'label' => 'Reddit',
            'icon'  => 'fab fa-reddit-alien',
            'color' => '#FF4500',
        ],
        self::TELEGRAM => [
            'label' => 'Telegram',
            'icon'  => 'fab fa-telegram-plane',
            'color' => '#0088CC',
        ],
        self::INSTAGRAM => [
            'label' => 'Instagram',
            'icon'  => 'fab fa-instagram',
            'color' => '#E1306C',
        ],
        self::PINTEREST => [
            'label' => 'Pinterest',
            'icon'  => 'fab fa-pinterest-p',
            'color' => '#E60023',
        ],
        self::YOUTUBE => [
            'label' => 'YouTube',
            'icon'  => 'fab fa-youtube',
            'color' => '#FF0000',
        ],
        self::TIKTOK => [
            'label' => 'TikTok',
            'icon'  => 'fab fa-tiktok',
            'color' => '#000000',
        ],
        self::SNAPCHAT => [
            'label' => 'Snapchat',
            'icon'  => 'fab fa-snapchat-ghost',
            'color' => '#FFFC00',
        ],
        self::TUMBLR => [
            'label' => 'Tumblr',
            'icon'  => 'fab fa-tumblr',
            'color' => '#36465D',
        ],
        self::MEDIUM => [
            'label' => 'Medium',
            'icon'  => 'fab fa-medium-m',
            'color' => '#00AB6C',
        ],
        self::VIMEO => [
            'label' => 'Vimeo',
            'icon'  => 'fab fa-vimeo-v',
            'color' => '#1AB7EA',
        ],
        self::GITHUB => [
            'label' => 'GitHub',
            'icon'  => 'fab fa-github',
            'color' => '#181717',
        ],
        self::STACKOVERFLOW => [
            'label' => 'Stack Overflow',
            'icon'  => 'fab fa-stack-overflow',
            'color' => '#F58025',
        ],
        self::DRIBBBLE => [
            'label' => 'Dribbble',
            'icon'  => 'fab fa-dribbble',
            'color' => '#EA4C89',
        ],
        self::FLICKR => [
            'label' => 'Flickr',
            'icon'  => 'fab fa-flickr',
            'color' => '#FF0084',
        ],
        self::DISCORD => [
            'label' => 'Discord',
            'icon'  => 'fab fa-discord',
            'color' => '#5865F2',
        ],
        self::SLACK => [
            'label' => 'Slack',
            'icon'  => 'fab fa-slack',
            'color' => '#4A154B',
        ],
        self::EMAIL => [
            'label' => 'Email',
            'icon'  => 'fas fa-envelope',
            'color' => '#DD4B39',
        ],
        self::WEBSITE => [
            'label' => 'Website',
            'icon'  => 'fas fa-globe',
            'color' => '#4CAF50',
        ],
    ];

    private function getMeta(): array
    {
        return self::$data[$this->value] ?? [
            'label' => ucwords(str_replace(['-', '_'], ' ', $this->value)),
            'icon'  => 'fas fa-share-alt',
            'color' => '#000000',
        ];
    }

    public function getLabel(): string
    {
        return $this->getMeta()['label'];
    }

    public function getIcon(): string
    {
        return $this->getMeta()['icon'];
    }

    public function getColor(): string
    {
        return $this->getMeta()['color'];
    }
}
