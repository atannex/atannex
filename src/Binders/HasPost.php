<?php

declare(strict_types=1);

namespace Atannex\Binders;

use Atannex\Components\FetchPostByContent;
use Atannex\Components\FetchPostsByHierarchy;
use Atannex\Components\FetchPostsWithHierarchy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

final class HasPost
{
    use FetchPostByContent;
    use FetchPostsByHierarchy;
    use FetchPostsWithHierarchy;

    /*
    |--------------------------------------------------------------------------
    | Post Query Constraints
    |--------------------------------------------------------------------------
    */

    private function applyPostConstraints(Builder|Relation $query, string $sortBy, string $sortDir, int $limit): Builder|Relation
    {
        return $query
            ->published()
            ->orderBy($sortBy, $sortDir)
            ->when($limit > 0, fn($q) => $q->limit($limit));
    }
}
