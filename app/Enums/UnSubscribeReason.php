<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Common reasons for unsubscribing from email newsletters or marketing lists.
 *
 * @method static static TooManyEmails()
 * @method static static NotRelevant()
 * @method static static NeverSubscribed()
 * @method static static TooMuchPromotion()
 * @method static static ChangedNeeds()
 * @method static static PreferOtherChannels()
 * @method static static NoLongerInterested()
 * @method static static Other()
 */
final class UnSubscribeReason extends Enum
{
    const TOO_MANY_EMAILS = 'too_many_emails';

    const NOT_RELEVANT = 'not_relevant';

    const NEVER_SUBSCRIBED = 'never_subscribed';

    const TOO_MUCH_PROMOTION = 'too_much_promotion';

    const CHANGED_NEEDS = 'changed_needs';

    const PREFER_OTHER_CHANNELS = 'prefer_other_channels';

    const NO_LONGER_INTERESTED = 'no_longer_interested';

    const OTHER = 'other';
}
