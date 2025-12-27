<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PostType extends Enum
{
    public const ARTICLE = 'article';

    public const VIDEO   = 'video';

    public const AUDIO   = 'audio';

}
