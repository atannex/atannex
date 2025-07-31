<?php

declare(strict_types=1);

namespace App\Enums\Auth;

use BenSampo\Enum\Enum;

final class AllowedDomain extends Enum
{
    const GMAIL_COM = 'gmail.com';
    const YAHOO_COM = 'yahoo.com';
    const OUTLOOK_COM = 'outlook.com';
    const HOTMAIL_COM = 'hotmail.com';
    const ICLOUD_COM = 'icloud.com';
    const AOL_COM = 'aol.com';
    const YANDEX_COM = 'yandex.com';
    const MAIL_RU = 'mail.ru';
    const GMX_COM = 'gmx.com';
    const PROTONMAIL_COM = 'protonmail.com';
}
