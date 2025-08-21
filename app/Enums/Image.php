<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

/**
 * ImageType Enum
 *
 * Defines various image types used in the application.
 */
final class Image extends Enum
{
    use GetEnum;

    public const LOGO = 'logo';
    public const BANNER = 'banner';
    public const THUMBNAIL = 'thumbnail';
    public const AVATAR = 'avatar';
    public const FAVICON = 'favicon';
    public const GALLERY = 'gallery';
    public const ADVERT = 'advert';
    public const SPONSOR_LOGO = 'sponsor_logo';
    public const COVER = 'cover';
    public const WATERMARK = 'watermark';

    /**
     * Boot method to set metadata for image types.
     */
    public static function boot(): void
    {
        static::setMetadata([
            self::LOGO => [
                'label' => 'Logo',
                'description' => 'Company or brand logo image',
                'color' => 'primary',
                'icon' => 'heroicon-o-identification',
            ],
            self::BANNER => [
                'label' => 'Banner',
                'description' => 'Large banner image for headers or promotions',
                'color' => 'info',
                'icon' => 'heroicon-o-rectangle-stack',
            ],
            self::THUMBNAIL => [
                'label' => 'Thumbnail',
                'description' => 'Small preview image, often used in listings',
                'color' => 'secondary',
                'icon' => 'heroicon-o-cube',
            ],
            self::AVATAR => [
                'label' => 'Avatar',
                'description' => 'User or profile avatar image',
                'color' => 'success',
                'icon' => 'heroicon-o-user-circle',
            ],
            self::FAVICON => [
                'label' => 'Favicon',
                'description' => 'Small icon displayed in browser tabs',
                'color' => 'warning',
                'icon' => 'heroicon-o-globe-alt',
            ],
            self::GALLERY => [
                'label' => 'Gallery',
                'description' => 'Grouped gallery images',
                'color' => 'amber',
                'icon' => 'heroicon-o-photograph',
            ],
            self::ADVERT => [
                'label' => 'Advertisement',
                'description' => 'Images used for ads or sponsored content',
                'color' => 'red',
                'icon' => 'heroicon-o-badge-check',
            ],
            self::SPONSOR_LOGO => [
                'label' => 'Sponsor Logo',
                'description' => 'Logos for sponsors or partners',
                'color' => 'teal',
                'icon' => 'heroicon-o-handshake',
            ],
            self::COVER => [
                'label' => 'Cover',
                'description' => 'Cover image for reports, collections, or eBooks',
                'color' => 'indigo',
                'icon' => 'heroicon-o-book-open',
            ],
            self::WATERMARK => [
                'label' => 'Watermark',
                'description' => 'Transparent watermark images',
                'color' => 'stone',
                'icon' => 'heroicon-o-adjustments',
            ],
        ]);
    }
}
