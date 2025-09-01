<?php

namespace Atannex;

use App\Enums\PostType;
use Atannex\Binders\GetComponent;
use Illuminate\Support\Collection;

final class AtannexProvider extends GetComponent
{
    /**
     * Map post types to their handler methods.
     *
     * @var array<string, string>
     */
    private array $postTypeMethods = [
        PostType::TRENDING_POST        => 'getTrendingPosts',
        PostType::POST_BY_CATEGORY     => 'GetPostsForCategory',
        PostType::POST_BY_TAG          => 'getPostByTag',
        PostType::POST_BY_FONDOM       => 'getPostByFondom',
        PostType::POST_BY_SUBDIVISION  => 'getPostBySubdivision',
    ];

    /**
     * Retrieve posts based on the specified type.
     *
     * The method checks if there is a dedicated method for the given post type.
     * If found, it delegates the retrieval to that method. Otherwise, it falls
     * back to retrieving only the just-published posts.
     *
     * @param array $config Configuration array containing:
     *                      - 'type' (string): The post type to fetch.
     *                      - Additional optional parameters for the specific retrieval method.
     *
     * @return \Illuminate\Support\Collection Collection of posts.
     */
    public function getPostsByType(array $config): Collection
    {
        if (isset($config['type'], $this->postTypeMethods[$config['type']])) {
            $method = $this->postTypeMethods[$config['type']];
            return $this->{$method}($config);
        }

        return $this->getJustPublishedPosts($config);
    }
}
