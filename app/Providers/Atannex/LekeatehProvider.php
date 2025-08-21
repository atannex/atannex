<?php

namespace App\Providers\Atannex;

use Atannex\Contracts\TagInterface;
use Atannex\Contracts\PageInterface;
use Atannex\Repositories\TagRepository;
use Illuminate\Support\ServiceProvider;
use Atannex\Contracts\CategoryInterface;
use Atannex\Contracts\DocumentInterface;
use Atannex\Repositories\PageRepository;
use Atannex\Repositories\CategoryRepository;
use Atannex\Repositories\DocumentRepository;

/**
 * Lekeateh service provider for binding repository implementations to interfaces.
 *
 * This provider ensures loose coupling by registering concrete classes
 * to their corresponding contracts for dependency injection.
 */
class LekeatehProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * Binds interface contracts to their concrete implementations.
     * This enables Laravel's service container to resolve dependencies
     * automatically based on the interface type hints.
     */
    public function register(): void
    {
        $this->app->bind(
            PageInterface::class,
            PageRepository::class
        );

        $this->app->bind(
            CategoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            DocumentInterface::class,
            DocumentRepository::class
        );

        $this->app->bind(
            TagInterface::class,
            TagRepository::class
        );
    }
}
