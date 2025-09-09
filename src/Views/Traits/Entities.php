<?php

namespace Atannex\Views\Traits;

use Illuminate\Support\Collection;
use App\Enums\PostType;

trait Entities
{
    /**
     * Resolve entities based on type, IDs, and full config.
     * Assumes $type is valid and mapping/method exists.
     */
    private function resolveEntities(string $type, array $ids, array $config = []): Collection
    {
        $mapping = PostType::getEntityMapping($type);

        $config[$mapping['idKey']] = $ids;

        return $this->getComponent->{$mapping['method']}($config);
    }
}
