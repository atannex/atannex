<?php

namespace App\Jobs\Posts;

use App\Models\Posts\Post;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BreakingPostJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;

    protected $news;

    public function __construct(Post $news)
    {
        $this->news = $news;
    }

    public function handle()
    {
        if ($this->news->is_breaking) {
            $this->news->update([
                'is_breaking' => false,
                'breaking_until' => null
            ]);
        }
    }
}
