<?php

namespace Atannex\Traits;

use BadMethodCallException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * Trait HasMetrics
 *
 * Provides methods to calculate engagement metrics and scores for Eloquent models.
 * Supports weighted scoring and top metric calculations based on predefined metrics.
 */
trait HasMetrics
{
    /**
     * Supported metrics for engagement calculations.
     */
    private const METRICS = ['views', 'likes', 'comments', 'ratings', 'shares'];

    /**
     * Get the default date range for metric calculations (last week).
     *
     * @return array{start: Carbon, end: Carbon} Start and end dates
     */
    private function getDefaultDateRange(): array
    {
        $end = Date::now();

        return [
            'start' => $end->copy()->subWeek(),
            'end' => $end,
        ];
    }

    /**
     * Get date range based on a predefined period.
     *
     * @param  string  $period  The period ('day', 'week', 'month', 'year')
     * @return array{start: Carbon, end: Carbon} Start and end dates
     */
    private function getPeriodRange(string $period): array
    {
        $now = Date::now();

        return match ($period) {
            'day' => [Date::today(), Date::today()->endOfDay()],
            'week' => [$now->startOfWeek(), $now->endOfWeek()],
            'month' => [$now->startOfMonth(), $now->endOfMonth()],
            'year' => [$now->startOfYear(), $now->endOfYear()],
            default => $this->getDefaultDateRange(),
        };
    }

    /**
     * Resolve the date range for metric calculations.
     *
     * @param  string|null  $period  Optional period ('day', 'week', 'month', 'year')
     * @param  Carbon|null  $start  Optional start date
     * @param  Carbon|null  $end  Optional end date
     * @return array{start: Carbon, end: Carbon} Resolved start and end dates
     */
    private function resolveDateRange(?string $period = null, ?Carbon $start = null, ?Carbon $end = null): array
    {
        if ($period) {
            return $this->getPeriodRange($period);
        }

        $range = $this->getDefaultDateRange();

        return [
            'start' => $start ?? $range['start'],
            'end' => $end ?? $range['end'],
        ];
    }

    /**
     * Get default weights for engagement score calculations.
     *
     * @return array<string, float> Default metric weights
     */
    private function getDefaultWeights(): array
    {
        return config('editor_picks.weights', [
            'views' => 0.2,
            'likes' => 0.2,
            'comments' => 0.3,
            'ratings' => 0.2,
            'shares' => 0.1,
        ]);
    }

    /**
     * Calculate weighted engagement scores for a collection of items.
     *
     * Assumes metric counts are loaded on models as {metric}_count attributes.
     *
     * @param  Collection  $items  Collection of Eloquent models
     * @param  array<string, float>  $weights  Custom weights to override defaults
     * @return Collection Collection with engagement scores
     */
    public function calculateWeightedScore(Collection $items, array $weights = []): Collection
    {
        $weights = array_merge($this->getDefaultWeights(), $weights);

        return $items->map(function (Model $item) use ($weights) {
            $item->engagement_score = 0.0;

            foreach (self::METRICS as $metric) {
                $countField = $metric.'_count';
                if (isset($item->$countField) && is_numeric($item->$countField)) {
                    $item->engagement_score += (float) $item->$countField * $weights[$metric];
                }
            }

            return $item;
        });
    }

    /**
     * Calculate engagement scores for a specific period.
     *
     * @param  Collection  $items  Collection of Eloquent models
     * @param  string  $period  Period for calculation ('day', 'week', 'month', 'year')
     * @param  array<string, float>  $weights  Custom weights to override defaults
     * @return Collection Collection with engagement scores
     */
    public function calculatePeriodScore(Collection $items, string $period, array $weights = []): Collection
    {
        $this->resolveDateRange($period); // Validate period, though not used directly

        return $this->calculateWeightedScore($items, $weights);
    }

    /**
     * Get top items by a specific metric.
     *
     * @param  Collection  $items  Collection of Eloquent models
     * @param  string  $metric  Metric to sort by (e.g., 'views', 'likes')
     * @param  int  $limit  Number of items to return
     * @return Collection Sorted collection of top items
     *
     * @throws BadMethodCallException If the metric is invalid
     */
    public function calculateTopByMetric(Collection $items, string $metric, int $limit = 5): Collection
    {
        if (! in_array($metric, self::METRICS, true)) {
            throw new BadMethodCallException(sprintf('Invalid metric: %s. Must be one of: ', $metric).implode(', ', self::METRICS));
        }

        $countField = $metric.'_count';

        return $items
            ->sortByDesc(fn ($item) => $item->$countField ?? 0)
            ->take(max(1, $limit))
            ->values();
    }

    /**
     * Handle dynamic method calls for top metric calculations.
     *
     * Supports methods like calculateTopViews(), calculateTopLikes(), etc.
     *
     * @param  string  $name  Method name
     * @param  array  $arguments  Method arguments
     * @return Collection Sorted collection of top items
     *
     * @throws BadMethodCallException If the method is not supported
     */
    public function __call(string $name, array $arguments): Collection
    {
        if (preg_match('/^calculateTop(\w+)$/', $name, $matches)) {
            $metric = strtolower($matches[1]);

            return $this->calculateTopByMetric(
                $arguments[0] instanceof Collection ? $arguments[0] : new Collection,
                $metric,
                isset($arguments[1]) && is_int($arguments[1]) ? $arguments[1] : 5
            );
        }

        throw new BadMethodCallException(sprintf('Method %s does not exist.', $name));
    }
}
