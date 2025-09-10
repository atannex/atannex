<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

final class Flag extends Enum
{
    use GetEnum;

    public const DRAFT = 'draft';

    public const PENDING = 'pending';

    public const REVIEWED = 'reviewed';

    public const SCHEDULED = 'scheduled';

    public const PUBLISHED = 'published';

    public const ARCHIVED = 'archived';

    public const DELETED = 'deleted';

    public const FEATURED = 'featured';

    public const BREAKING = 'breaking';

    public const FACT_CHECKED = 'fact_checked';

    public const EDITORIAL_PICK = 'editorial_pick';

    public static function boot(): void
    {
        self::setMetadata([
            self::DRAFT => [
                'label' => 'Draft',
                'description' => 'Post in initial creation stage',
                'color' => 'secondary',
                'icon' => 'heroicon-o-pencil-square',
            ],
            self::PENDING => [
                'label' => 'Pending',
                'description' => 'Post awaiting review or approval',
                'color' => 'info',
                'icon' => 'heroicon-o-clock',
            ],
            self::REVIEWED => [
                'label' => 'Reviewed',
                'description' => 'Post has been reviewed and awaits final approval',
                'color' => 'warning',
                'icon' => 'heroicon-o-eye',
            ],
            self::SCHEDULED => [
                'label' => 'Scheduled',
                'description' => 'Post scheduled for publication',
                'color' => 'info',
                'icon' => 'heroicon-o-calendar',
            ],
            self::PUBLISHED => [
                'label' => 'Published',
                'description' => 'Post is live and publicly visible',
                'color' => 'success',
                'icon' => 'heroicon-o-globe-alt',
            ],
            self::ARCHIVED => [
                'label' => 'Archived',
                'description' => 'Post stored for historical reference',
                'color' => 'secondary',
                'icon' => 'heroicon-o-archive-box',
            ],
            self::DELETED => [
                'label' => 'Deleted',
                'description' => 'Post marked for deletion',
                'color' => 'danger',
                'icon' => 'heroicon-o-trash',
            ],
            self::FEATURED => [
                'label' => 'Featured',
                'description' => 'Post highlighted as a featured item',
                'color' => 'warning',
                'icon' => 'heroicon-o-star',
            ],
            self::BREAKING => [
                'label' => 'Breaking',
                'description' => 'High-priority breaking news post',
                'color' => 'danger',
                'icon' => 'heroicon-o-fire',
            ],
            self::FACT_CHECKED => [
                'label' => 'Fact-Checked',
                'description' => 'Post verified for accuracy',
                'color' => 'info',
                'icon' => 'heroicon-o-shield-check',
            ],
            self::EDITORIAL_PICK => [
                'label' => 'Editorial Pick',
                'description' => 'Post selected by editors as noteworthy',
                'color' => 'primary',
                'icon' => 'heroicon-o-bookmark',
            ],
        ]);
    }
}
