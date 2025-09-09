<?php

namespace Atannex;

use App\Enums\PostType;
use Atannex\Binders\GetComponent;
use Illuminate\Support\Collection;

final class AtannexProvider extends GetComponent
{
    /**
     * Retrieve posts based on the specified type.
     *
     * This directly delegates the retrieval to the mapped method in PostType.
     *
     * @param array $config Configuration array containing:
     *                      - 'type' (string): The post type to fetch.
     *                      - Additional optional parameters for the specific retrieval method.
     *
     * @return Collection Collection of posts.
     */
    public function getPostsByType(array $config): Collection
    {
        $mapping = PostType::getEntityMapping($config['type']);
        return $this->{$mapping['method']}($config);
    }
}
