<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Social media and website icons with metadata.
 */
final class Icon extends Enum
{
    public const FACEBOOK      = 'facebook';

    public const TWITTER       = 'twitter';

    public const LINKEDIN      = 'linkedin';

    public const WHATSAPP      = 'whatsapp';

    public const REDDIT        = 'reddit';

    public const TELEGRAM      = 'telegram';

    public const INSTAGRAM     = 'instagram';

    public const PINTEREST     = 'pinterest';

    public const YOUTUBE       = 'youtube';

    public const TIKTOK        = 'tiktok';

    public const SNAPCHAT      = 'snapchat';

    public const TUMBLR        = 'tumblr';

    public const MEDIUM        = 'medium';

    public const VIMEO         = 'vimeo';

    public const GITHUB        = 'github';

    public const STACKOVERFLOW = 'stackoverflow';

    public const DRIBBBLE      = 'dribbble';

    public const FLICKR        = 'flickr';

    public const DISCORD       = 'discord';

    public const SLACK         = 'slack';

    public const EMAIL         = 'email';

    public const WEBSITE       = 'website';

    private static array $data = [
        self::FACEBOOK      => ['label' => 'Facebook',       'icon' => 'fab fa-facebook-f',     'color' => '#1877F2'],
        self::TWITTER       => ['label' => 'Twitter',        'icon' => 'fab fa-twitter',        'color' => '#1DA1F2'],
        self::LINKEDIN      => ['label' => 'LinkedIn',       'icon' => 'fab fa-linkedin-in',    'color' => '#0077B5'],
        self::WHATSAPP      => ['label' => 'WhatsApp',       'icon' => 'fab fa-whatsapp',       'color' => '#25D366'],
        self::REDDIT        => ['label' => 'Reddit',         'icon' => 'fab fa-reddit',         'color' => '#FF4500'],
        self::TELEGRAM      => ['label' => 'Telegram',       'icon' => 'fab fa-telegram',       'color' => '#0088CC'],
        self::INSTAGRAM     => ['label' => 'Instagram',      'icon' => 'fab fa-instagram',      'color' => '#E1306C'],
        self::PINTEREST     => ['label' => 'Pinterest',      'icon' => 'fab fa-pinterest',      'color' => '#E60023'],
        self::YOUTUBE       => ['label' => 'YouTube',        'icon' => 'fab fa-youtube',        'color' => '#FF0000'],
        self::TIKTOK        => ['label' => 'TikTok',         'icon' => 'fab fa-tiktok',         'color' => '#000000'],
        self::SNAPCHAT      => ['label' => 'Snapchat',       'icon' => 'fab fa-snapchat',       'color' => '#FFFC00'],
        self::TUMBLR        => ['label' => 'Tumblr',         'icon' => 'fab fa-tumblr',         'color' => '#36465D'],
        self::MEDIUM        => ['label' => 'Medium',         'icon' => 'fab fa-medium',         'color' => '#00AB6C'],
        self::VIMEO         => ['label' => 'Vimeo',          'icon' => 'fab fa-vimeo-v',        'color' => '#1AB7EA'],
        self::GITHUB        => ['label' => 'GitHub',         'icon' => 'fab fa-github',         'color' => '#181717'],
        self::STACKOVERFLOW => ['label' => 'Stack Overflow', 'icon' => 'fab fa-stack-overflow', 'color' => '#F58025'],
        self::DRIBBBLE      => ['label' => 'Dribbble',       'icon' => 'fab fa-dribbble',       'color' => '#EA4C89'],
        self::FLICKR        => ['label' => 'Flickr',         'icon' => 'fab fa-flickr',         'color' => '#FF0084'],
        self::DISCORD       => ['label' => 'Discord',        'icon' => 'fab fa-discord',        'color' => '#5865F2'],
        self::SLACK         => ['label' => 'Slack',          'icon' => 'fab fa-slack',          'color' => '#4A154B'],
        self::EMAIL         => ['label' => 'Email',          'icon' => 'fas fa-envelope',       'color' => '#DD4B39'],
        self::WEBSITE       => ['label' => 'Website',        'icon' => 'fas fa-globe',          'color' => '#4CAF50'],
    ];

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

    public static function getData(string $icon): array
    {
        return self::$data[$icon];
    }

    public static function all(): array
    {
        return self::$data;
    }
}
