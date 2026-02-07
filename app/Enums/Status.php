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
}
