<?php

namespace App\Providers\Atannex;

use Atangageih\Contracts\PageInterface;
use Illuminate\Support\ServiceProvider;
use Atangageih\Contracts\CategoryInterface;
use Atangageih\Contracts\DocumentInterface;
use Atangageih\Repositories\PageRepository;
use Atangageih\Repositories\CategoryRepository;
use Atangageih\Repositories\DocumentRepository;

class LekeatehProvider extends ServiceProvider
{
    /**
     * Register services.
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
