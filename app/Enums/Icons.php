<?php

declare(strict_types=1);

namespace App\Enums;

use Atannex\Filters\GetEnum;
use BenSampo\Enum\Enum;

/**
 * Enum representing social media platforms for sharing.
 *
 * Provides metadata such as label, color, icon, and share URL.
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
    use GetEnum;

    public const TWITTER   = 'twitter';

    public const FACEBOOK  = 'facebook';

    public const LINKEDIN  = 'linkedin';

    public const WHATSAPP  = 'whatsapp';

    public const REDDIT    = 'reddit';

    public const PINTEREST = 'pinterest';

    public const TELEGRAM  = 'telegram';

    /**
     * Initialize metadata for all platforms.
     *
     * This method should be called once before accessing enum metadata.
     */
    public static function boot(): void
    {
        self::setMetadata([
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
        ]);
    }
}
