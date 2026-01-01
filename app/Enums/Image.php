<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Image Enum
 *
 * Defines various image types used in the application.
 */
final class Image extends Enum
{
    #[Description('Logo')]
    public const LOGO = 'logo';

    #[Description('Banner')]
    public const BANNER = 'banner';

    #[Description('Thumbnail')]
    public const THUMBNAIL = 'thumbnail';

    #[Description('Avatar')]
    public const AVATAR = 'avatar';

    #[Description('Favicon')]
    public const FAVICON = 'favicon';

    #[Description('Gallery')]
    public const GALLERY = 'gallery';

    #[Description('Advertisement')]
    public const ADVERT = 'advert';

    #[Description('Sponsor Logo')]
    public const SPONSOR_LOGO = 'sponsor_logo';

    #[Description('Cover')]
    public const COVER = 'cover';

    #[Description('Watermark')]
    public const WATERMARK = 'watermark';

    #[Description('Subscription')]
    public const SUBSCRIPTION = 'subscription';
}
