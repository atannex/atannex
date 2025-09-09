<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class MakeModelEventSetup extends Command
{
    protected $signature = 'atannex:model-event-setup';

    protected $description = 'Interactively generate an Event, Listener, and Job, optionally tied to a model';

    public function handle()
    {
        // --- Optional: select a model ---
        // --- Recursive scan of Models folder ---
        $allModelFiles = File::allFiles(app_path('Models'));

        $models = collect($allModelFiles)
            ->map(function ($file) {
                $relative = $file->getRelativePathname();  // e.g., "Admin/User.php"
                return str_replace(['/', '.php'], ['\\', ''], $relative); // "Admin\User"
            })
            ->toArray();

        $modelName = null;
        if (!empty($models)) {
            $modelName = $this->choice(
                'Select a model to associate with this event (or skip)',
                array_merge(['None'], $models),
                0
            );
            if ($modelName === 'None') {
                $modelName = null;
            }
        }

        // --- Prompt for event, listener, job names ---
        $eventName = $this->ask('Enter Event class name', $modelName ? $modelName . 'Created' : null);
        $listenerName = $this->ask('Enter Listener class name', $modelName ? sprintf('Send%sNotification', $modelName) : null);
        $jobName = $this->ask('Enter Job class name', $modelName ? sprintf('Process%sJob', $modelName) : null);

        $this->info('Creating scaffolding for event: ' . $eventName);

        // --- 1. Create Event ---
        Artisan::call('make:event', ['name' => $eventName]);
        $this->info('Event created: ' . $eventName);

        // --- 2. Create Listener ---
        Artisan::call('make:listener', [
            'name' => $listenerName,
            '--event' => $eventName
        ]);
        $this->info('Listener created: ' . $listenerName);

        // --- 3. Create Job ---
        Artisan::call('make:job', ['name' => $jobName]);
        $this->info('Job created: ' . $jobName);

        // --- 4. Add use statements and register listener ---
        $this->addUseStatements($eventName, $listenerName);
        $this->registerEventListener($eventName, $listenerName);

        // --- 5. Update listener to dispatch job ---
        $this->updateListenerToDispatchJob($listenerName, $jobName);

        $this->info("Scaffolding done successfully.");
    }

    protected function addUseStatements(string $event, string $listener)
    {
        $providerPath = app_path('Providers/EventServiceProvider.php');
        if (!File::exists($providerPath)) {
            $this->error('EventServiceProvider not found: ' . $providerPath);
            return;
        }

        $contents = File::get($providerPath);
        $useStatements = [];

        if (strpos($contents, sprintf('use App\Events\%s;', $event)) === false) {
            $useStatements[] = sprintf('use App\Events\%s;', $event);
        }

        if (strpos($contents, sprintf('use App\Listeners\%s;', $listener)) === false) {
            $useStatements[] = sprintf('use App\Listeners\%s;', $listener);
        }

        if ($useStatements === []) {
            return;
        }

        preg_match_all('/^use [^;]+;$/m', $contents, $matches, PREG_OFFSET_CAPTURE);
        $insertPos = $matches[0] ? end($matches[0])[1] + strlen(end($matches[0])[0])
            : strpos($contents, ';') + 1;

        $contents = substr_replace($contents, "\n" . implode("\n", $useStatements), $insertPos, 0);
        File::put($providerPath, $contents);

        foreach ($useStatements as $stmt) {
            $this->info('Added use statement: ' . $stmt);
        }
    }

    protected function registerEventListener(string $event, string $listener)
    {
        $providerPath = app_path('Providers/EventServiceProvider.php');
        if (!File::exists($providerPath)) {
            $this->error('EventServiceProvider not found: ' . $providerPath);
            return;
        }

        $contents = File::get($providerPath);
        $listenerClass = $listener . '::class';

        // Check if event already exists
        $eventPattern = '/(' . preg_quote($event, '/') . '::class\s*=>\s*\[)([^\]]*?)(\],?)/s';
        if (preg_match($eventPattern, $contents, $matches)) {
            $existingListeners = trim($matches[2]);
            if (strpos($existingListeners, $listenerClass) !== false) {
                $this->info(sprintf("Listener '%s' already registered for event '%s'.", $listener, $event));
                return;
            }

            $newListeners = $existingListeners !== '' && $existingListeners !== '0' ? $existingListeners . (',
        ' . $listenerClass) : '
        ' . $listenerClass;
            $replacement = $matches[1] . $newListeners . "\n    " . $matches[3];
            $contents = str_replace($matches[0], $replacement, $contents);

            File::put($providerPath, $contents);
            $this->info(sprintf("Listener '%s' added to existing event '%s'.", $listener, $event));
            return;
        }

        // Event doesn't exist, add new event-listener pair
        $listenPattern = '/(protected\s+\$listen\s*=\s*\[)(.*?)(\n\s*\];)/s';
        if (preg_match($listenPattern, $contents, $matches)) {
            $beforeClosing = rtrim($matches[2]);
            $newEntry = "\n        {$event}::class => [\n            {$listener}::class,\n        ],";

            if ($beforeClosing !== '' && $beforeClosing !== '0' && !str_ends_with(trim($beforeClosing), ',')) {
                $beforeClosing .= ',';
            }

            $replacement = $matches[1] . $beforeClosing . $newEntry . $matches[3];
            $contents = str_replace($matches[0], $replacement, $contents);

            File::put($providerPath, $contents);
            $this->info(sprintf("Event '%s' with listener '%s' registered in EventServiceProvider.", $event, $listener));
        } else {
            $this->error("Could not find \$listen array in EventServiceProvider.");
        }
    }

    protected function updateListenerToDispatchJob(string $listener, string $job)
    {
        $listenerPath = app_path(sprintf('Listeners/%s.php', $listener));
        if (!File::exists($listenerPath)) {
            $this->error('Listener file not found: ' . $listenerPath);
            return;
        }

        $contents = File::get($listenerPath);
        $jobUse = sprintf('use App\Jobs\%s;', $job);

        if (strpos($contents, $jobUse) === false) {
            preg_match_all('/^use [^;]+;$/m', $contents, $matches, PREG_OFFSET_CAPTURE);
            $insertPos = end($matches[0])[1] + strlen(end($matches[0])[0]);
            $contents = substr_replace($contents, PHP_EOL . $jobUse, $insertPos, 0);
        }

        $handlePattern = '/public function handle\([^)]*\)\s*\{([\s\S]*?)\}/';
        if (preg_match($handlePattern, $contents, $matches)) {
            $body = trim($matches[1]);
            if (!str_contains($body, $job . '::dispatch')) {
                $newBody = $body !== '' && $body !== '0' ? $body . "\n        {$job}::dispatch(\$event);"
                    : "\n        {$job}::dispatch(\$event);";
                $contents = str_replace($matches[0], "public function handle(\$event) {\n        {$newBody}\n    }", $contents);
            }
        }

        File::put($listenerPath, $contents);
        $this->info(sprintf("Updated listener '%s' to dispatch job '%s'", $listener, $job));
    }
}
