<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Icon Enum
 *
 * Represents social media and website icons with metadata.
 */
final class Icon extends Enum
{
    #[Description('Facebook')]
    public const FACEBOOK = 'facebook';

    #[Description('Twitter')]
    public const TWITTER = 'twitter';

    #[Description('LinkedIn')]
    public const LINKEDIN = 'linkedin';

    #[Description('WhatsApp')]
    public const WHATSAPP = 'whatsapp';

    #[Description('Reddit')]
    public const REDDIT = 'reddit';

    #[Description('Telegram')]
    public const TELEGRAM = 'telegram';

    #[Description('Instagram')]
    public const INSTAGRAM = 'instagram';

    #[Description('Pinterest')]
    public const PINTEREST = 'pinterest';

    #[Description('YouTube')]
    public const YOUTUBE = 'youtube';

    #[Description('TikTok')]
    public const TIKTOK = 'tiktok';

    #[Description('Snapchat')]
    public const SNAPCHAT = 'snapchat';

    #[Description('Tumblr')]
    public const TUMBLR = 'tumblr';

    #[Description('Medium')]
    public const MEDIUM = 'medium';

    #[Description('Vimeo')]
    public const VIMEO = 'vimeo';

    #[Description('GitHub')]
    public const GITHUB = 'github';

    #[Description('Stack Overflow')]
    public const STACKOVERFLOW = 'stackoverflow';

    #[Description('Dribbble')]
    public const DRIBBBLE = 'dribbble';

    #[Description('Flickr')]
    public const FLICKR = 'flickr';

    #[Description('Discord')]
    public const DISCORD = 'discord';

    #[Description('Slack')]
    public const SLACK = 'slack';

    #[Description('Email')]
    public const EMAIL = 'email';

    #[Description('Website')]
    public const WEBSITE = 'website';

    /**
     * Icon metadata: label, icon class, color.
     */
    private static array $data = [
        self::FACEBOOK => ['label' => 'Facebook',       'icon' => 'fab fa-facebook-f',     'color' => '#1877F2'],
        self::TWITTER => ['label' => 'Twitter',        'icon' => 'fab fa-twitter',        'color' => '#1DA1F2'],
        self::LINKEDIN => ['label' => 'LinkedIn',      'icon' => 'fab fa-linkedin-in',    'color' => '#0077B5'],
        self::WHATSAPP => ['label' => 'WhatsApp',      'icon' => 'fab fa-whatsapp',       'color' => '#25D366'],
        self::REDDIT => ['label' => 'Reddit',          'icon' => 'fab fa-reddit',         'color' => '#FF4500'],
        self::TELEGRAM => ['label' => 'Telegram',      'icon' => 'fab fa-telegram',       'color' => '#0088CC'],
        self::INSTAGRAM => ['label' => 'Instagram',    'icon' => 'fab fa-instagram',      'color' => '#E1306C'],
        self::PINTEREST => ['label' => 'Pinterest',    'icon' => 'fab fa-pinterest',      'color' => '#E60023'],
        self::YOUTUBE => ['label' => 'YouTube',        'icon' => 'fab fa-youtube',        'color' => '#FF0000'],
        self::TIKTOK => ['label' => 'TikTok',          'icon' => 'fab fa-tiktok',         'color' => '#000000'],
        self::SNAPCHAT => ['label' => 'Snapchat',      'icon' => 'fab fa-snapchat',       'color' => '#FFFC00'],
        self::TUMBLR => ['label' => 'Tumblr',          'icon' => 'fab fa-tumblr',         'color' => '#36465D'],
        self::MEDIUM => ['label' => 'Medium',          'icon' => 'fab fa-medium',         'color' => '#00AB6C'],
        self::VIMEO => ['label' => 'Vimeo',            'icon' => 'fab fa-vimeo-v',        'color' => '#1AB7EA'],
        self::GITHUB => ['label' => 'GitHub',          'icon' => 'fab fa-github',         'color' => '#181717'],
        self::STACKOVERFLOW => ['label' => 'Stack Overflow', 'icon' => 'fab fa-stack-overflow', 'color' => '#F58025'],
        self::DRIBBBLE => ['label' => 'Dribbble',      'icon' => 'fab fa-dribbble',       'color' => '#EA4C89'],
        self::FLICKR => ['label' => 'Flickr',          'icon' => 'fab fa-flickr',         'color' => '#FF0084'],
        self::DISCORD => ['label' => 'Discord',        'icon' => 'fab fa-discord',        'color' => '#5865F2'],
        self::SLACK => ['label' => 'Slack',            'icon' => 'fab fa-slack',          'color' => '#4A154B'],
        self::EMAIL => ['label' => 'Email',            'icon' => 'fas fa-envelope',       'color' => '#DD4B39'],
        self::WEBSITE => ['label' => 'Website',        'icon' => 'fas fa-globe',          'color' => '#4CAF50'],
    ];

    /**
     * Get a metadata value by key for this icon.
     */
    private function meta(string $key): string
    {
        return self::$data[$this->value][$key];
    }

    public function label(): string
    {
        return $this->meta('label');
    }

    public function icon(): string
    {
        return $this->meta('icon');
    }

    public function color(): string
    {
        return $this->meta('color');
    }

    /**
     * Get all metadata for a given icon constant.
     */
    public static function getData(string $icon): array
    {
        return self::$data[$icon];
    }

    /**
     * Get metadata for all icons.
     */
    public static function all(): array
    {
        return self::$data;
    }
}
