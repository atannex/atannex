<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class MakeModelEventSetup extends Command
{
    protected $signature = 'make:model-event {model : The name of the model}';
    protected $description = 'Generate Event, Listener, Job for a given model and register them in EventServiceProvider';

    public function handle()
    {
        $model = Str::studly($this->argument('model'));
        $eventName = "{$model}Created";
        $listenerName = "Send{$model}Notification";
        $jobName = "Process{$model}Job";

        $this->info("Creating scaffolding for model: $model");

        // 1. Create Event
        Artisan::call('make:event', ['name' => $eventName]);
        $this->info("Event created: $eventName");

        // 2. Create Listener
        Artisan::call('make:listener', [
            'name' => $listenerName,
            '--event' => $eventName
        ]);
        $this->info("Listener created: $listenerName");

        // 3. Create Job
        Artisan::call('make:job', ['name' => $jobName]);
        $this->info("Job created: $jobName");

        // 4. Ensure use statements exist
        $this->addUseStatements($eventName, $listenerName);

        // 5. Register Event and Listener
        $this->registerEventListener($eventName, $listenerName);

        // 6. Update listener to dispatch the job
        $this->updateListenerToDispatchJob($listenerName, $jobName);

        $this->info("All scaffolding done successfully.");
    }

    protected function addUseStatements(string $event, string $listener)
    {
        $providerPath = app_path('Providers/EventServiceProvider.php');
        if (!File::exists($providerPath)) {
            $this->error("EventServiceProvider not found: $providerPath");
            return;
        }

        $contents = File::get($providerPath);
        $useStatements = [];

        if (strpos($contents, "use App\\Events\\$event;") === false) {
            $useStatements[] = "use App\\Events\\$event;";
        }
        if (strpos($contents, "use App\\Listeners\\$listener;") === false) {
            $useStatements[] = "use App\\Listeners\\$listener;";
        }

        if (empty($useStatements)) {
            return; // Nothing to add
        }

        // Find last use statement position
        preg_match_all('/^use [^;]+;$/m', $contents, $matches, PREG_OFFSET_CAPTURE);
        $insertPos = $matches[0] ? end($matches[0])[1] + strlen(end($matches[0])[0])
                                 : strpos($contents, ';') + 1;

        $contents = substr_replace($contents, "\n" . implode("\n", $useStatements), $insertPos, 0);
        File::put($providerPath, $contents);

        foreach ($useStatements as $stmt) {
            $this->info("Added use statement: $stmt");
        }
    }

    protected function registerEventListener(string $event, string $listener)
    {
        $providerPath = app_path('Providers/EventServiceProvider.php');
        if (!File::exists($providerPath)) {
            $this->error("EventServiceProvider not found: $providerPath");
            return;
        }

        $contents = File::get($providerPath);
        $listenerClass = "$listener::class";

        // Check if event already exists
        $eventPattern = '/(' . preg_quote($event, '/') . '::class\s*=>\s*\[)([^\]]*?)(\],?)/s';
        if (preg_match($eventPattern, $contents, $matches)) {
            $existingListeners = trim($matches[2]);

            if (strpos($existingListeners, $listenerClass) !== false) {
                $this->info("Listener '$listener' already registered for event '$event'.");
                return;
            }

            $newListeners = $existingListeners ? $existingListeners . ",\n        $listenerClass" : "\n        $listenerClass";
            $replacement = $matches[1] . $newListeners . "\n    " . $matches[3];
            $contents = str_replace($matches[0], $replacement, $contents);

            File::put($providerPath, $contents);
            $this->info("Listener '$listener' added to existing event '$event'.");
            return;
        }

        // Event doesn't exist, add new event-listener pair
        $listenPattern = '/(protected\s+\$listen\s*=\s*\[)(.*?)(\n\s*\];)/s';
        if (preg_match($listenPattern, $contents, $matches)) {
            $beforeClosing = rtrim($matches[2]);
            $newEntry = "\n        $event::class => [\n            $listener::class,\n        ],";

            if (!empty($beforeClosing) && !str_ends_with(trim($beforeClosing), ',')) {
                $beforeClosing .= ',';
            }

            $replacement = $matches[1] . $beforeClosing . $newEntry . $matches[3];
            $contents = str_replace($matches[0], $replacement, $contents);

            File::put($providerPath, $contents);
            $this->info("Event '$event' with listener '$listener' registered in EventServiceProvider.");
        } else {
            $this->error("Could not find \$listen array in EventServiceProvider.");
        }
    }

    protected function updateListenerToDispatchJob(string $listener, string $job)
    {
        $listenerPath = app_path("Listeners/$listener.php");
        if (!File::exists($listenerPath)) {
            $this->error("Listener file not found: $listenerPath");
            return;
        }

        $contents = File::get($listenerPath);
        $jobUse = "use App\\Jobs\\$job;";

        if (strpos($contents, $jobUse) === false) {
            preg_match_all('/^use [^;]+;$/m', $contents, $matches, PREG_OFFSET_CAPTURE);
            $insertPos = end($matches[0])[1] + strlen(end($matches[0])[0]);
            $contents = substr_replace($contents, "\n$jobUse", $insertPos, 0);
        }

        // Safely insert dispatch into handle method
        $handlePattern = '/public function handle\([^)]*\)\s*\{([\s\S]*?)\}/';
        if (preg_match($handlePattern, $contents, $matches)) {
            $body = trim($matches[1]);
            if (!str_contains($body, "$job::dispatch")) {
                $newBody = $body ? $body . "\n        $job::dispatch(\$event);"
                                 : "\n        $job::dispatch(\$event);";
                $contents = str_replace($matches[0], "public function handle(\$event) {\n        $newBody\n    }", $contents);
            }
        }

        File::put($listenerPath, $contents);
        $this->info("Updated listener '$listener' to dispatch job '$job'");
    }
}
