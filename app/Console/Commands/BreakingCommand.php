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
     * Execute the console command.
     *
     * This command:
     * - Finds posts currently marked as breaking
     * - Checks whether their breaking expiration time has passed
     * - Resets all breaking-related fields in a single atomic update
     *
     * Why a command (instead of inline scheduler logic):
     * - Keeps Console\Kernel clean
     * - Allows manual execution for maintenance/debugging
     * - Improves testability and reuse
     */
    public function handle(): int
    {
        $affected = Post::query()
            ->where('is_breaking', true)
            ->whereNotNull('breaking_expires')
            ->where('breaking_expires', '<=', now())
            ->update([
                'is_breaking'       => false,
                'breaking_at'       => null,
                'breaking_expires'  => null,
            ]);

        $this->info("Breaking cleanup completed. {$affected} post(s) updated.");

        return Command::SUCCESS;
    }
}
