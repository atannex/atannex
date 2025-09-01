<?php

namespace Atannex\Components\For\Editorials;

use Carbon\Carbon;
use Atannex\Traits\FetchEngagedPosts;

trait GetEditorWeeklyPick
{
    use FetchEngagedPosts;

    public function getEditorWeeklyPicks(array $config = [])
    {
        $config['start'] = $config['start'] ?? Carbon::now()->startOfWeek();
        $config['end']   = $config['end'] ?? Carbon::now()->endOfWeek();

        return $this->fetchEngagedPosts($config);
    }
}
