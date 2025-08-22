<?php

namespace Atannex\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Metrics
{
    private const DEFAULT_WEIGHTS = [
        'views' => 0.2,
        'likes' => 0.2,
        'comments' => 0.3,
        'ratings' => 0.2,
        'shares' => 0.1,
    ];

    private const METRICS = ['views', 'likes', 'comments', 'ratings', 'shares'];

    private function getDefaultDateRange(): array
    {
        return [
            'start' => Carbon::now()->subWeek(),
            'end' => Carbon::now(),
        ];
    }

    /**
     * Calculate weighted engagement score for a collection of models.
     *
     * @param Collection $items
     * @param array $weights Associative weights for metrics
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @return Collection
     */
    public function calculateWeightedScore(
        Collection $items,
        array $weights = [],
        ?Carbon $start = null,
        ?Carbon $end = null
    ): Collection {
        $dateRange = $this->getDefaultDateRange();
        $start = $start ?? $dateRange['start'];
        $end = $end ?? $dateRange['end'];
        $weights = array_merge(self::DEFAULT_WEIGHTS, $weights);

        return $items->each(function (Model $item) use ($weights, $start, $end) {
            $score = 0;
            foreach (self::METRICS as $metric) {
                $count = method_exists($item, $metric)
                    ? $item->{$metric}()->whereBetween('created_at', [$start, $end])->count()
                    : 0;
                $score += $count * $weights[$metric];
            }
            $item->engagement_score = $score;
        });
    }

    /**
     * Calculate engagement score for predefined time periods
     *
     * @param Collection $items
     * @param string $period day|week|month|year
     * @param array $weights
     * @return Collection
     */
    public function calculatePeriodScore(Collection $items, string $period, array $weights = []): Collection
    {
        $ranges = [
            'day' => [Carbon::today(), Carbon::today()->endOfDay()],
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
        ];

        return $this->calculateWeightedScore($items, $weights, ...$ranges[$period]);
    }

    /**
     * Get top posts by a single metric
     *
     * @param Collection $items
     * @param string $metric
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @param int $limit
     * @return Collection
     */
    public function calculateTopByMetric(
        Collection $items,
        string $metric,
        ?Carbon $start = null,
        ?Carbon $end = null,
        int $limit = 5
    ): Collection {
        $dateRange = $this->getDefaultDateRange();
        $start = $start ?? $dateRange['start'];
        $end = $end ?? $dateRange['end'];

        return $items->sortByDesc(function (Model $item) use ($metric, $start, $end) {
            return method_exists($item, $metric)
                ? $item->{$metric}()->whereBetween('created_at', [$start, $end])->count()
                : 0;
        })->take($limit)->values();
    }

    /**
     * Generate top metric methods dynamically
     *
     * @param string $name
     * @param array $arguments
     * @return Collection
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $arguments): Collection
    {
        if (preg_match('/^calculateTop(\w+)$/', $name, $matches)) {
            $metric = strtolower($matches[1]);
            if (in_array($metric, self::METRICS)) {
                return $this->calculateTopByMetric(
                    $arguments[0] ?? new Collection(),
                    $metric,
                    $arguments[1] ?? null,
                    $arguments[2] ?? null,
                    $arguments[3] ?? 5
                );
            }
        }

        throw new \BadMethodCallException("Method {$name} does not exist.");
    }
}
