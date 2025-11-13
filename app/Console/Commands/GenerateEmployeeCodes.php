<?php

namespace App\Console\Commands;

use App\Models\Regions\Employee;
use Illuminate\Console\Command;

class GenerateEmployeeCodes extends Command
{
    protected $signature = 'employees:generate-codes';

    protected $description = 'Regenerate employee codes for all employees based on user names';

    public function handle()
    {
        $this->info('Starting employee code generation...');

        // Eager load 'user' to avoid N+1 problem
        $employees = Employee::with('user')->get();

        if ($employees->isEmpty()) {
            $this->info('No employees found.');

            return 0;
        }

        $bar = $this->output->createProgressBar($employees->count());
        $bar->start();

        foreach ($employees as $employee) {
            // The trait now safely handles null users
            $employee->generateEmployeeCode();
            $employee->save();
            $bar->advance();
        }

        $bar->finish();

        $this->info("\nEmployee code generation completed successfully!");

        return 0;
    }
}
