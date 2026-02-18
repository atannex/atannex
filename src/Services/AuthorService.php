<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Posts\Post;
use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final class AuthorService
{
    public function postsByAuthor(string $slug, int $limit = 10): LengthAwarePaginator
    {
        $authorId = Employee::query()
            ->whereHas('user', fn ($q) => $q->where('slug', $slug))
            ->value('id');

        if ($authorId === null) {
            return Post::query()
                ->whereRaw('1 = 0')
                ->paginate($limit);
        }

        return Post::query()
            ->published()
            ->where('author_id', $authorId)
            ->with([
                'category.parent',
                'region',
                'tags',
                'author.user',
            ])
            ->latest()
            ->paginate($limit);
    }

    /**
     * Resolve author by user slug.
     */
    public function resolveAuthorBySlug(string $slug): Employee
    {
        return Employee::query()
            ->with(['user:id,slug'])
            ->whereHas(
                'user',
                fn (Builder $query) => $query->where('slug', $slug)
            )
            ->firstOrFail();
    }
}
