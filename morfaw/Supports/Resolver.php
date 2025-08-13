<?php

namespace Morfaw\Supports;

use App\Models\Pages\Category;
use App\Models\Regions\Employee;

trait Resolver
{
    /**
     * Resolve an employee author by the user's slug.
     *
     * @param string $slug
     * @return Employee|null
     */
    protected function resolveAuthorBySlug(string $slug): ?Employee
    {
        return Employee::whereHas('user', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->with('user')->first();
    }
}
