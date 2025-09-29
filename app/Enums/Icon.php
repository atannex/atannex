<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Icon extends Enum
{
    const FACEBOOK = 'facebook';

    const TWITTER = 'twitter';

    const LINKEDIN = 'linkedin';

    const WHATSAPP = 'whatsapp';

    const REDDIT = 'reddit';

    const TELEGRAM = 'telegram';

    /**
     * Metadata for each platform
     */
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
    ];

    /**
     * Get metadata for a platform
     */
    public static function getData(string $platform): array
    {
        return self::$data[$platform] ?? [
            'label' => ucwords(str_replace(['-', '_'], ' ', $platform)),
            'icon'  => 'fas fa-share-alt',
            'color' => '#000000',
        ];
    }
}
