<?php

namespace Atannex;

use App\Enums\PostType;
use Atannex\Binders\GetComponent;
use Illuminate\Support\Collection;

final class Atannex extends GetComponent
{
    /**
     * Map post types to callables.
     *
     * @var array<string, callable>
     */
    private array $postTypeMethods = [];

    public function __construct()
    {
        $this->postTypeMethods = [
            PostType::EDITOR_PICK           => $this->getEditorPicks(),
            PostType::EDITOR_WEEKLY_PICK    => $this->getEditorWeeklyPicks(),
            PostType::TRENDING_POST         => $this->getTrendingPosts(),
            PostType::HEADLINE_OF_THE_DAY   => $this->getHeadlinesOfTheDay(),
            PostType::FEATURED_POST         => $this->getFeaturedPosts(),
            PostType::POST_BY_TAG           => fn(array $config): Collection => $this->getPostByTag($config),
            PostType::BREAKING_POST         => fn(array $config): Collection => $this->getBreakingPosts($config),
            PostType::POST_BY_CATEGORY      => fn(array $config): Collection => $this->getPostByCategory($config),
            PostType::POST_BY_FONDOM        => fn(array $config): Collection => $this->getPostByFondom($config),
            PostType::POST_BY_SUBDIVISION   => fn(array $config): Collection => $this->getPostBySubdivision($config),
        ];
    }

    /**
     * Get posts based on type.
     *
     * @param array $config
     * @return Collection
     */
    public function getPostsByType(array $config): Collection
    {
        $postType = $config['type'] ?? null;
        $callback = $this->postTypeMethods[$postType] ?? null;

        if (!$callback) {
            return $this->getJustPublishedPosts($config);
        }

        return $callback($config);
    }
}
