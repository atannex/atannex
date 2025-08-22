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
        PostType::BREAKING_POST        => 'getBreakingPosts',
        // PostType::EDITOR_PICK         => 'getEditorPicks',
        PostType::EDITOR_WEEKLY_PICK   => 'getEditorWeeklyPicks',
        PostType::TRENDING_POST        => 'getTrendingPosts',
        PostType::HEADLINE_OF_THE_DAY  => 'getHeadlinesOfTheDay',
        PostType::POST_BY_CATEGORY     => 'getPostByCategory',
        // PostType::FEATURED_POST       => 'getFeaturedPosts',
        PostType::POST_BY_TAG          => 'getPostByTag',
        PostType::POST_BY_FONDOM       => 'getPostByFondom',
        PostType::POST_BY_SUBDIVISION  => 'getPostBySubdivision',
    ];

    /**
     * Get posts based on type.
     *
     * @param array $config
     * @return Collection
     */
    public function getPostsByType(array $config): Collection
    {
        $postType = $config['type'] ?? null;

        if (isset($this->postTypeMethods[$postType])) {
            $method = $this->postTypeMethods[$postType];
            return $this->{$method}($config);
        }

        return $this->getJustPublishedPosts($config);
    }
}
