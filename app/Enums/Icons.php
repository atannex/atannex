<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

/**
 * Enum representing popular social media platforms used in blog/news websites.
 * Each item includes UI metadata (label, Bootstrap color, FontAwesome icon).
 *
 * @method static static TWITTER()
 * @method static static FACEBOOK()
 * @method static static INSTAGRAM()
 * @method static static LINKEDIN()
 * @method static static YOUTUBE()
 * @method static static PINTEREST()
 * @method static static REDDIT()
 * @method static static WHATSAPP()
 * @method static static TELEGRAM()
 * @method static static TUMBLR()
 * @method static static SNAPCHAT()
 * @method static static TIKTOK()
 * @method static static DISCORD()
 * @method static static MEDIUM()
 * @method static static GITHUB()
 * @method static static SLACK()
 */
final class Icons extends Enum
{
    use GetEnum;

    public const TWITTER    = 'twitter';
    public const FACEBOOK   = 'facebook';
    public const INSTAGRAM  = 'instagram';
    public const LINKEDIN   = 'linkedin';
    public const YOUTUBE    = 'youtube';
    public const PINTEREST  = 'pinterest';
    public const REDDIT     = 'reddit';
    public const WHATSAPP   = 'whatsapp';
    public const TELEGRAM   = 'telegram';
    public const TUMBLR     = 'tumblr';
    public const SNAPCHAT   = 'snapchat';
    public const TIKTOK     = 'tiktok';
    public const DISCORD    = 'discord';
    public const MEDIUM     = 'medium';
    public const GITHUB     = 'github';
    public const SLACK      = 'slack';

    /**
     * Set UI metadata for each social media constant.
     */
    public static function boot(): void
    {
        static::setMetadata([
            self::TWITTER    => [
                'label' => 'Twitter',
                'color' => 'primary',
                'icon' => 'fab fa-twitter'
            ],
            self::FACEBOOK   => ['label' => 'Facebook',   'color' => 'primary',   'icon' => 'fab fa-facebook-f'],
            self::INSTAGRAM  => ['label' => 'Instagram',  'color' => 'danger',    'icon' => 'fab fa-instagram'],
            self::LINKEDIN   => ['label' => 'LinkedIn',   'color' => 'primary',   'icon' => 'fab fa-linkedin-in'],
            self::YOUTUBE    => ['label' => 'YouTube',    'color' => 'danger',    'icon' => 'fab fa-youtube'],
            self::PINTEREST  => ['label' => 'Pinterest',  'color' => 'danger',    'icon' => 'fab fa-pinterest-p'],
            self::REDDIT     => ['label' => 'Reddit',     'color' => 'danger',    'icon' => 'fab fa-reddit-alien'],
            self::WHATSAPP   => ['label' => 'WhatsApp',   'color' => 'success',   'icon' => 'fab fa-whatsapp'],
            self::TELEGRAM   => ['label' => 'Telegram',   'color' => 'info',      'icon' => 'fab fa-telegram-plane'],
            self::TUMBLR     => ['label' => 'Tumblr',     'color' => 'primary',   'icon' => 'fab fa-tumblr'],
            self::SNAPCHAT   => ['label' => 'Snapchat',   'color' => 'warning',   'icon' => 'fab fa-snapchat-ghost'],
            self::TIKTOK     => ['label' => 'TikTok',     'color' => 'dark',      'icon' => 'fab fa-tiktok'],
            self::DISCORD    => ['label' => 'Discord',    'color' => 'primary',   'icon' => 'fab fa-discord'],
            self::MEDIUM     => ['label' => 'Medium',     'color' => 'dark',      'icon' => 'fab fa-medium-m'],
            self::GITHUB     => ['label' => 'GitHub',     'color' => 'dark',      'icon' => 'fab fa-github'],
            self::SLACK      => ['label' => 'Slack',      'color' => 'danger',    'icon' => 'fab fa-slack-hash'],
        ]);
    }
}
