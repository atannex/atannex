<?php

namespace Atangageih\Services;

use App\Models\Pages\Page;
use Illuminate\Support\Collection;
use Atangageih\Contracts\PageInterface;

final class PageService
{
    public function __construct(protected readonly PageInterface $interface)
    {
        //
    }

    public function getHomePage(string $slug): ?Page
    {
        return $this->interface->getHomePage($slug);
    }

    public function getAllCategoryPages(): Collection
    {
        return $this->interface->getAllCategoryPages();
    }

    public function getAllHomePages(): Collection
    {
        return $this->interface->getAllHomePages();
    }
}
