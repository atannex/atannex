<?php

namespace Atannex;

use App\Enums\PostType;
use Atannex\Binders\GetComponent;
use Illuminate\Support\Collection;

final class Atannex extends GetComponent
{
    private const POST_TYPE_METHODS = [
        PostType::BREAKING_POST        => 'getBreakingPosts',
        PostType::EDITOR_PICK      => 'getEditorPicks',
        PostType::EDITOR_WEEKLY_PICK   => 'getEditorWeeklyPicks',
        PostType::TRENDING_POST     => 'getTrendingPosts',
        PostType::HEADLINE_OF_THE_DAY  => 'getHeadlinesOfTheDay',
        PostType::POST_BY_CATEGORY     => 'getPostByCategory',
        PostType::FEATURED_POST      => 'getFeaturedPosts',
        PostType::POST_BY_TAG       => 'getPostByTag',
        PostType::POST_BY_FONDOM     => 'getPostByFondom',
        PostType::POST_BY_SUBDIVISION  => 'getPostBySubdivision',
    ];

    public function getPostsByType(array $config): Collection
    {
        $postType = $config['type'] ?? null;
        $methodName = self::POST_TYPE_METHODS[$postType] ?? null;

        if (!$methodName || !method_exists($this, $methodName)) {
            return $this->getJustPublishedPosts($config);
        }

        return $this->$methodName($config);
    }
}
