<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Enum representing social media platforms for sharing posts.
 *
 * @method static self FACEBOOK()
 * @method static self X()
 * @method static self LINKEDIN()
 * @method static self WHATSAPP()
 * @method static self REDDIT()
 * @method static self PINTEREST()
 * @method static self TELEGRAM()
 * @method static self TUMBLR()
 */
final class Social extends Enum
{
    public const FACEBOOK = 'facebook';
    public const X = 'x';
    public const LINKEDIN = 'linkedin';
    public const WHATSAPP = 'whatsapp';
    public const REDDIT = 'reddit';
    public const PINTEREST = 'pinterest';
    public const TELEGRAM = 'telegram';
    public const TUMBLR = 'tumblr';

    private const PLATFORM_DATA = [
        self::FACEBOOK => [
            'label' => 'Facebook',
            'icon' => 'fab fa-facebook-f',
            'color' => '#1877F2',
            'share_url' => 'https://www.facebook.com/sharer/sharer.php?u=',
        ],
        self::X => [
            'label' => 'X',
            'icon' => 'fab fa-twitter',
            'color' => '#000000',
            'share_url' => 'https://x.com/intent/tweet?url=',
        ],
        self::LINKEDIN => [
            'label' => 'LinkedIn',
            'icon' => 'fab fa-linkedin-in',
            'color' => '#0A66C2',
            'share_url' => 'https://www.linkedin.com/sharing/share-offsite/?url=',
        ],
        self::WHATSAPP => [
            'label' => 'WhatsApp',
            'icon' => 'fab fa-whatsapp',
            'color' => '#25D366',
            'share_url' => 'https://api.whatsapp.com/send?text=',
        ],
        self::REDDIT => [
            'label' => 'Reddit',
            'icon' => 'fab fa-reddit',
            'color' => '#FF4500',
            'share_url' => 'https://www.reddit.com/submit?url=',
        ],
        self::PINTEREST => [
            'label' => 'Pinterest',
            'icon' => 'fab fa-pinterest',
            'color' => '#E60023',
            'share_url' => 'https://pinterest.com/pin/create/button/?url=',
        ],
        self::TELEGRAM => [
            'label' => 'Telegram',
            'icon' => 'fab fa-telegram',
            'color' => '#0088CC',
            'share_url' => 'https://t.me/share/url?url=',
        ],
        self::TUMBLR => [
            'label' => 'Tumblr',
            'icon' => 'fab fa-tumblr',
            'color' => '#36465D',
            'share_url' => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=',
        ],
    ];

    /**
     * Get platform data for a specific platform.
     */
    public static function getPlatformData(string $value): array
    {
        if (!isset(self::PLATFORM_DATA[$value])) {
            throw new \InvalidArgumentException("Invalid social platform: {$value}");
        }
        return self::PLATFORM_DATA[$value];
    }

    /**
     * Get the label for a platform.
     */
    public static function getLabel(string $value): string
    {
        return self::getPlatformData($value)['label'];
    }

    /**
     * Get the Font Awesome icon class for a platform.
     */
    public static function getIconClass(string $value): string
    {
        return self::getPlatformData($value)['icon'];
    }

    /**
     * Get the brand color for a platform.
     */
    public static function getColor(string $value): string
    {
        return self::getPlatformData($value)['color'];
    }

    /**
     * Get the share URL for a platform.
     */
    public static function getShareUrl(string $value): string
    {
        return self::getPlatformData($value)['share_url'];
    }

    /**
     * Get all platform data.
     */
    public static function getAllPlatforms(): array
    {
        return self::PLATFORM_DATA;
    }
}
