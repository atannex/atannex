<?php

namespace Atannex\Components\For\Get;

use Carbon\Carbon;
use Atannex\Traits\FetchEngagedPosts;

trait GetHeadlineOfTheDay
{
    use FetchEngagedPosts;

    public function getHeadlinesOfTheDay(array $config = [])
    {
        $config['start'] = $config['start'] ?? Carbon::today();
        $config['end']   = $config['end'] ?? Carbon::today()->endOfDay();
        $config['recency_boost'] = $config['prioritize_recency'] ?? true;

        return $this->fetchEngagedPosts($config);
    }
}
