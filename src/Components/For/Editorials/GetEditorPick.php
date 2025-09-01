<?php

namespace Atannex\Components\For\Editorials;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetEditorPick
{
    /**
     * Retrieve posts marked as editor's picks within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     */
    public function getEditorPicks(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now()->subDays(30);
        $end = $end ?? Carbon::now();

        return Post::query()
            ->published()
            ->isEditorPick()
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
