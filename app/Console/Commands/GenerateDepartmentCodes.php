<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Regions\Department;

class GenerateDepartmentCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example usage: php artisan departments:generate-codes
     */
    protected $signature = 'departments:generate-codes {--force : Regenerate codes even if they exist}';

    /**
     * The console command description.
     */
    protected $description = 'Generate or regenerate department codes for all departments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $departments = Department::all();
        $this->info(sprintf('Found %d departments...', $departments->count()));

        foreach ($departments as $department) {
            if ($this->option('force') || empty($department->department_code)) {
                $department->generateDepartmentCode();
                $department->save();

                $this->line(sprintf('✅ Updated: %s → %s', $department->name, $department->department_code));
            } else {
                $this->line(sprintf('⏭ Skipped: %s (already has code: %s)', $department->name, $department->department_code));
            }
        }

        $this->info("🎉 Department code generation completed!");
    }
}
