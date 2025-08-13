<?php

namespace App\Providers\Atannex;

use Atangageih\Contracts\TagInterface;
use Atangageih\Contracts\PageInterface;
use Illuminate\Support\ServiceProvider;
use Atangageih\Repositories\TagRepository;
use Atangageih\Contracts\CategoryInterface;
use Atangageih\Contracts\DocumentInterface;
use Atangageih\Repositories\PageRepository;
use Atangageih\Repositories\CategoryRepository;
use Atangageih\Repositories\DocumentRepository;

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
        // Bind the PageInterface to the PageRepository
        $this->app->bind(
            PageInterface::class,
            PageRepository::class
        );

        // Bind the CategoryInterface to the CategoryRepository
        $this->app->bind(
            CategoryInterface::class,
            CategoryRepository::class
        );

        // Bind the DocumentInterface to the DocumentRepository
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
