<?php

namespace Atannex\Traits;

use BadMethodCallException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

trait HasMetrics
{
    private const METRICS = ['views', 'likes', 'comments', 'ratings', 'shares'];

    private function getDefaultDateRange(): array
    {
        $end = Date::now();

        return [
            'start' => $end->copy()->subWeek(),
            'end' => $end,
        ];
    }

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

    public function calculatePeriodScore(Collection $items, string $period, array $weights = []): Collection
    {
        $this->resolveDateRange($period);

        return $this->calculateWeightedScore($items, $weights);
    }

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
