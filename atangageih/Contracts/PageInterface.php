<?php

namespace Atangageih\Contracts;

use App\Models\Pages\Page;
use Illuminate\Support\Collection;

interface PageInterface
{
    public function getHomePage(string $slugPath): ?Page;

    public function getAllCategoryPages(): Collection;

    public function getAllHomePages(): Collection;
}
