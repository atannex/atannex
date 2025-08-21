<?php

namespace Atannex\Services\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

trait HandlesExceptions
{
    /**
     * Safely execute a callback with error logging and optional fallback.
     *
     * @template T
     * @param callable(): T $callback
     * @param string|null $contextMessage
     * @param T|null $fallback
     * @param array $extraContext
     * @return T|null
     */
    protected function safely(callable $callback, ?string $contextMessage = null, mixed $fallback = null, array $extraContext = []): mixed
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            $this->logException($e, $contextMessage, $extraContext);
            return $fallback;
        }
    }

    /**
     * Log an exception with context information.
     *
     * @param Throwable $e
     * @param string|null $message
     * @param array $context
     * @return void
     */
    protected function logException(Throwable $e, ?string $message = null, array $context = []): void
    {
        $logMessage = $message ?? $e->getMessage();

        Log::warning($logMessage, array_merge([
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
        ], $context));
    }
}
