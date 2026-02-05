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

    #[Description('Verified')]
    public const VERIFIED = 'verified';

    /** Fully active user */
    #[Description('Active')]
    public const ACTIVE = 'active';

    /** Limited access (temporary or conditional) */
    #[Description('Restricted')]
    public const RESTRICTED = 'restricted';

    /** Temporarily disabled by system or admin */
    #[Description('Suspended')]
    public const SUSPENDED = 'suspended';

    #[Description('Suspicious')]
    public const SUSPICIOUS = 'suspicious';

    /** Permanently blocked */
    #[Description('Banned')]
    public const BANNED = 'banned';

    /** Soft-deleted / no longer usable */
    #[Description('Deleted')]
    public const DELETED = 'deleted';

    /**
     * Get the valid next states for a given status.
     *
     * @param string $currentStatus The current status value.
     * @return array<int, string> An array of allowed next status values (each as a string).
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
     * Determine whether transitioning from one status to another is permitted.
     *
     * @param string $currentStatus The current status identifier.
     * @param string $targetStatus The desired target status identifier.
     * @return bool `true` if the transition from `$currentStatus` to `$targetStatus` is allowed, `false` otherwise.
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
