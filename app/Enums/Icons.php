<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Enum representing social media platforms for sharing.
 *
 * Each constant defines a platform, and associated metadata is stored
 * in a private, static array for centralized access.
 *
 * @method static static TWITTER()
 * @method static static FACEBOOK()
 * @method static static LINKEDIN()
 * @method static static WHATSAPP()
 * @method static static REDDIT()
 * @method static static PINTEREST()
 * @method static static TELEGRAM()
 */
final class Icons extends Enum
{
    public const TWITTER   = 'twitter';
    public const FACEBOOK  = 'facebook';
    public const LINKEDIN  = 'linkedin';
    public const WHATSAPP  = 'whatsapp';
    public const REDDIT    = 'reddit';
    public const PINTEREST = 'pinterest';
    public const TELEGRAM  = 'telegram';

    /**
     * Platform metadata: label, color, icon, and share URL.
     *
     * @var array<string, array{label: string, color: string, icon: string, share_url: string}>
     */
    protected static array $metadata = [
        self::TWITTER => [
            'label'     => 'Twitter',
            'color'     => '#000000',
            'icon'      => 'fab fa-twitter',
            'share_url' => 'https://x.com/intent/tweet?url=',
        ],
        self::FACEBOOK => [
            'label'     => 'Facebook',
            'color'     => '#1877F2',
            'icon'      => 'fab fa-facebook-f',
            'share_url' => 'https://www.facebook.com/sharer/sharer.php?u=',
        ],
        self::LINKEDIN => [
            'label'     => 'LinkedIn',
            'color'     => '#0A66C2',
            'icon'      => 'fab fa-linkedin-in',
            'share_url' => 'https://www.linkedin.com/sharing/share-offsite/?url=',
        ],
        self::PINTEREST => [
            'label'     => 'Pinterest',
            'color'     => '#E60023',
            'icon'      => 'fab fa-pinterest-p',
            'share_url' => 'https://pinterest.com/pin/create/button/?url=',
        ],
        self::REDDIT => [
            'label'     => 'Reddit',
            'color'     => '#FF4500',
            'icon'      => 'fab fa-reddit-alien',
            'share_url' => 'https://www.reddit.com/submit?url=',
        ],
        self::WHATSAPP => [
            'label'     => 'WhatsApp',
            'color'     => '#25D366',
            'icon'      => 'fab fa-whatsapp',
            'share_url' => 'https://api.whatsapp.com/send?text=',
        ],
        self::TELEGRAM => [
            'label'     => 'Telegram',
            'color'     => '#0088CC',
            'icon'      => 'fab fa-telegram-plane',
            'share_url' => 'https://t.me/share/url?url=',
        ],
    ];

    /**
     * Get the label for a given platform value.
     */
    public static function getLabel(string $platform): string
    {
        return self::$metadata[$platform]['label'];
    }

    /**
     * Get the FontAwesome icon for a given platform value.
     */
    public static function getIcon(string $platform): string
    {
        return self::$metadata[$platform]['icon'];
    }

    /**
     * Get the color for a given platform value.
     */
    public static function getColor(string $platform): string
    {
        return self::$metadata[$platform]['color'];
    }

    /**
     * Get the share URL template for a given platform value.
     */
    public static function getShareUrl(string $platform): string
    {
        return self::$metadata[$platform]['share_url'];
    }

    /**
     * Get all metadata for a specific platform.
     *
     * @return array<string, string>
     */
    public static function getPlatformData(string $platform): array
    {
        return self::$metadata[$platform];
    }

    /**
     * Get all platforms with their full metadata.
     *
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        return self::$metadata;
    }
}
