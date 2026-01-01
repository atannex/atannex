<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Flag Enum
 *
 * Represents the editorial workflow and publication
 * state of a news/post record.
 *
 * NOTE:
 * Although named "Flag", values are mutually exclusive
 * and represent a single active workflow state.
 */
final class Flag extends Enum
{
    #[Description('Draft')]
    public const DRAFT = 'draft';

    #[Description('Pending Review')]
    public const PENDING_REVIEW = 'pending_review';

    #[Description('In Review')]
    public const IN_REVIEW = 'in_review';

    #[Description('Revision Needed')]
    public const REVISION_NEEDED = 'revision_needed';

    #[Description('Approved')]
    public const APPROVED = 'approved';

    #[Description('Scheduled')]
    public const SCHEDULED = 'scheduled';

    #[Description('Published')]
    public const PUBLISHED = 'published';

    #[Description('Archived')]
    public const ARCHIVED = 'archived';

    #[Description('Deleted')]
    public const DELETED = 'deleted';
}
