<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

/**
 * UserStatus Enum (Filament Optimized with Bootstrap colors)
 *
 * Defines the status of a user account for use within the Filament admin panel.
 * Ordered according to typical user account lifecycle transitions.
 */
final class Status extends Enum
{
    use GetEnum;

    public const PENDING = 'pending';
    public const VERIFY = 'verify';
    public const ACTIVE = 'active';
    public const SUSPENDED = 'suspended';
    public const BANNED = 'banned';
    public const DELETED = 'deleted';
    public const RESTRICTED = 'restricted';
    public const REVIEWED = 'reviewed';
    public const RESPONDED = 'responded';
    public const CLOSED = 'closed';

    /**
     * Initialize metadata for all user status types with Bootstrap colors.
     */
    public static function boot(): void
    {
        static::setMetadata([
            self::PENDING => [
                'label' => 'Pending',
                'description' => 'User account awaiting activation or review',
                'color' => 'info',
                'icon' => 'heroicon-o-clock',
            ],
            self::VERIFY => [
                'label' => 'Verify',
                'description' => 'User account pending verification',
                'color' => 'primary',
                'icon' => 'heroicon-o-check',
            ],
            self::ACTIVE => [
                'label' => 'Active',
                'description' => 'User account is fully active',
                'color' => 'success',
                'icon' => 'heroicon-o-check-circle',
            ],
            self::SUSPENDED => [
                'label' => 'Suspended',
                'description' => 'User account temporarily disabled',
                'color' => 'warning',
                'icon' => 'heroicon-o-pause',
            ],
            self::BANNED => [
                'label' => 'Banned',
                'description' => 'User account permanently banned',
                'color' => 'danger',
                'icon' => 'heroicon-o-x-circle',
            ],
            self::DELETED => [
                'label' => 'Deleted',
                'description' => 'User account marked for deletion',
                'color' => 'danger',
                'icon' => 'heroicon-o-trash',
            ],
            self::RESTRICTED => [
                'label' => 'Restricted',
                'description' => 'User account with limited permissions',
                'color' => 'warning',
                'icon' => 'heroicon-o-lock-closed',
            ],
        ]);
    }
}
