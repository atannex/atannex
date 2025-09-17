<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use App\Enums\Platforms\HasFacebook;
use App\Enums\Platforms\HasLinkedIn;
use App\Enums\Platforms\HasPinterest;
use App\Enums\Platforms\HasReddit;
use App\Enums\Platforms\HasTelegram;
use App\Enums\Platforms\HasTwitter;
use App\Enums\Platforms\HasWhatsapp;

trait HasSocialSharing
{
    use HasFacebook;
    use HasLinkedIn;
    use HasPinterest;
    use HasReddit;
    use HasTelegram;
    use HasTwitter;
    use HasWhatsapp;
}
