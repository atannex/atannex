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
        $now = Carbon::now();
        return [
            'start' => $now->subWeek(),
            'end' => $now,
        ];
    }

    private function getPeriodRange(string $period): array
    {
        $now = Carbon::now();
        return match ($period) {
            'day' => [Carbon::today(), Carbon::today()->endOfDay()],
            'week' => [$now->startOfWeek(), $now->endOfWeek()],
            'month' => [$now->startOfMonth(), $now->endOfMonth()],
            'year' => [$now->startOfYear(), $now->endOfYear()],
            default => $this->getDefaultDateRange(),
        };
    }

    /**
     * Calculate weighted engagement score for a collection of models.
     */
    public function calculateWeightedScore(
        Collection $items,
        array $weights = [],
        ?Carbon $start = null,
        ?Carbon $end = null
    ): Collection {
        $weights = array_merge(self::DEFAULT_WEIGHTS, $weights);
        $range = [
            'start' => $start ?? $this->getDefaultDateRange()['start'],
            'end' => $end ?? $this->getDefaultDateRange()['end'],
        ];

        return $items->map(function (Model $item) use ($weights, $range) {
            $score = 0;
            foreach (self::METRICS as $metric) {
                if (method_exists($item, $metric)) {
                    $score += $item->{$metric}()
                        ->whereBetween('created_at', [$range['start'], $range['end']])
                        ->count() * $weights[$metric];
                }
            }
            $item->engagement_score = $score;
            return $item;
        });
    }

    /**
     * Calculate engagement score for a predefined time period.
     */
    public function calculatePeriodScore(Collection $items, string $period, array $weights = []): Collection
    {
        [$start, $end] = $this->getPeriodRange($period);
        return $this->calculateWeightedScore($items, $weights, $start, $end);
    }

    /**
     * Get top posts by a single metric.
     */
    public function calculateTopByMetric(
        Collection $items,
        string $metric,
        ?Carbon $start = null,
        ?Carbon $end = null,
        int $limit = 5
    ): Collection {
        if (!in_array($metric, self::METRICS)) {
            return new Collection();
        }

        $range = [
            'start' => $start ?? $this->getDefaultDateRange()['start'],
            'end' => $end ?? $this->getDefaultDateRange()['end'],
        ];

        return $items
            ->map(function (Model $item) use ($metric, $range) {
                $item->metric_count = method_exists($item, $metric)
                    ? $item->{$metric}()->whereBetween('created_at', [$range['start'], $range['end']])->count()
                    : 0;
                return $item;
            })
            ->sortByDesc('metric_count')
            ->take($limit)
            ->values();
    }

    /**
     * Dynamic top metric methods.
     */
    public function __call(string $name, array $arguments): Collection
    {
        if (preg_match('/^calculateTop(\w+)$/', $name, $matches)) {
            $metric = strtolower($matches[1]);
            return $this->calculateTopByMetric(
                $arguments[0] ?? new Collection(),
                $metric,
                $arguments[1] ?? null,
                $arguments[2] ?? null,
                $arguments[3] ?? 5
            );
        }

        throw new \BadMethodCallException("Method {$name} does not exist.");
    }
}
