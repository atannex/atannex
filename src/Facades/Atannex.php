<?php

namespace Atannex\Facades;

use App\Enums\Traits\HasEntityMapping;
use Atannex\Binders\HasComponent;
use Illuminate\Support\Collection;

/**
 * Class AtannexProvider
 *
 * Service provider for retrieving posts based on entity types.
 * Extends base Components class and uses HasEntityMapping trait
 * to dynamically resolve methods according to the entity type.
 *
 * @package Atannex
 */
final class Atannex extends HasComponent
{
    use HasEntityMapping;

    /**
     * Retrieve posts by the specified entity type.
     *
     * @param array $config Configuration array containing at least a 'type' key.
     * @return Collection Returns a Laravel Collection of posts.
     * Resolve the corresponding method name dynamically using the trait
     * Call the resolved method with the given configuration
     *
     */
    public function getPostsByType(array $config): Collection
    {
        $entity = $config['type'];
        $methodName = self::resolveMethod($entity);
        return $this->$methodName($config);
    }
}
