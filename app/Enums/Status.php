<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * UserStatus Enum
 *
 * Represents the global lifecycle and moderation state
 * of a user account across the system.
 */
final class Status extends Enum
{
    /** User created but not yet approved */
    #[Description('Pending')]
    public const PENDING = 'pending';

    /** User must verify account (email / phone / admin) */
    #[Description('Unverified')]
    public const UNVERIFIED = 'unverified';

    /** Fully active user */
    #[Description('Active')]
    public const ACTIVE = 'active';

    /** Limited access (temporary or conditional) */
    #[Description('Restricted')]
    public const RESTRICTED = 'restricted';

    /** Temporarily disabled by system or admin */
    #[Description('Suspended')]
    public const SUSPENDED = 'suspended';

    /** Permanently blocked */
    #[Description('Banned')]
    public const BANNED = 'banned';

    /** Soft-deleted / no longer usable */
    #[Description('Deleted')]
    public const DELETED = 'deleted';

    /**
     * Returns the list of valid next states for a given status.
     *
     * @return array<int, string>
     */
    public static function allowedTransitions(string $currentStatus): array
    {
        return match ($currentStatus) {
            self::PENDING => [self::UNVERIFIED, self::DELETED],
            self::UNVERIFIED => [self::ACTIVE, self::DELETED],
            self::ACTIVE => [self::RESTRICTED, self::SUSPENDED, self::DELETED],
            self::RESTRICTED => [self::ACTIVE, self::SUSPENDED, self::DELETED],
            self::SUSPENDED => [self::ACTIVE, self::BANNED, self::DELETED],
            self::BANNED => [self::DELETED],
            self::DELETED => [],
            default => [],
        };
    }

    /**
     * Check if a transition from current status to target status is allowed.
     */
    public static function canTransition(string $currentStatus, string $targetStatus): bool
    {
        return in_array($targetStatus, self::allowedTransitions($currentStatus), true);
    }

    public static function describe(?string $value): string
    {
        return $value ? self::getDescription($value) : 'N/A';
    }
}
