<?php

declare(strict_types=1);

namespace App\Enums\Auth;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * AllowedDomain Enum
 *
 * Represents email domains allowed for registration.
 */
final class AllowedDomain extends Enum
{
    #[Description('Gmail')]
    public const GMAIL_COM = 'gmail.com';

    #[Description('Yahoo')]
    public const YAHOO_COM = 'yahoo.com';

    #[Description('Outlook')]
    public const OUTLOOK_COM = 'outlook.com';

    #[Description('Hotmail')]
    public const HOTMAIL_COM = 'hotmail.com';

    #[Description('iCloud')]
    public const ICLOUD_COM = 'icloud.com';

    #[Description('AOL')]
    public const AOL_COM = 'aol.com';

    #[Description('Yandex')]
    public const YANDEX_COM = 'yandex.com';

    #[Description('Mail.ru')]
    public const MAIL_RU = 'mail.ru';

    #[Description('GMX')]
    public const GMX_COM = 'gmx.com';

    #[Description('ProtonMail')]
    public const PROTONMAIL_COM = 'protonmail.com';
}
