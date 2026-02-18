<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Posts\Post;
use Illuminate\Console\Command;

class BreakingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * This command is intended to be executed by the scheduler.
     */
    protected $signature = 'atannex:breaking-cleanup';

    /**
     * The console command description.
     */
    protected $description = 'Clear expired breaking news posts';

    /**
     * Clear expired breaking news flags from posts.
     *
     * Finds posts whose breaking_expires timestamp has passed and resets their breaking-related fields in a single atomic update.
     *
     * @return int Exit status code; `Command::SUCCESS` on success.
     */
    public function handle(): int
    {
        $affected = Post::query()
            ->where('is_breaking', true)
            ->whereNotNull('breaking_expires')
            ->where('breaking_expires', '<=', now())
            ->update([
                'is_breaking' => false,
                'breaking_at' => null,
                'breaking_expires' => null,
            ]);

        $this->info("Breaking cleanup completed. {$affected} post(s) updated.");

        return Command::SUCCESS;
    }
}
