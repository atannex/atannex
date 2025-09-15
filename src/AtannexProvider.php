<?php

namespace Atannex;

use App\Enums\Traits\HasEntityMapping;
use Atannex\Binders\Components;
use Illuminate\Support\Collection;

final class AtannexProvider extends Components
{
    use HasEntityMapping;

    public function getPostsByType(array $config): Collection
    {
        $entity = $config['type'];
        $methodName = self::resolveMethod($entity);
        return $this->$methodName($config);
    }
}
