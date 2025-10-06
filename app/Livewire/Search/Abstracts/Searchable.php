<?php

namespace App\Livewire\Search\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

abstract class Searchable extends Component
{
    use WithPagination;

    public string $query = '';

    public int $perPage = 10;

    public string $sortBy = 'latest';

    protected const VALID_SORT_OPTIONS = ['latest', 'oldest'];

    protected const DEFAULT_SORT = 'latest';

    protected const DEFAULT_PER_PAGE = 10;

    /**
     * Define the base query for the search.
     * Child classes may override if needed, but by default it includes eager-loaded relations.
     */
    protected function baseQuery(): Builder
    {
        return $this->newModelQuery()->with($this->relationsToEagerLoad());
    }

    /**
     * Return a fresh model query. Must be implemented by child classes.
     */
    abstract protected function newModelQuery(): Builder;

    /**
     * Specify the view to render.
     */
    abstract protected function view(): string;

    /**
     * Define the fields to search on.
     *
     * @return array<string>
     */
    abstract protected function searchableFields(): array;

    /**
     * Reset pagination when the search query changes.
     */
    public function updatingQuery(): void
    {
        $this->resetPage();
    }

    /**
     * Set the sorting option and reset pagination.
     */
    public function setSortBy(string $sortBy): void
    {
        $this->sortBy = $this->isValidSortOption($sortBy) ? $sortBy : self::DEFAULT_SORT;
        $this->resetPage();
    }

    /**
     * Render the component view with paginated results.
     */
    public function render(): View
    {
        $queryBuilder = $this->applyFilters($this->baseQuery());

        return view($this->view(), [
            'items' => $queryBuilder->paginate($this->sanitizePerPage($this->perPage)),
        ]);
    }

    /**
     * Apply search and sorting filters to the query.
     */
    protected function applyFilters(Builder $queryBuilder): Builder
    {
        return $this->applySorting(
            $this->applySearch($queryBuilder)
        );
    }

    /**
     * Apply search conditions to the query.
     */
    protected function applySearch(Builder $queryBuilder): Builder
    {
        $searchQuery = trim($this->query);

        if ($searchQuery === '' || $searchQuery === '0') {
            return $queryBuilder;
        }

        $search = '%' . $this->sanitizeSearch($searchQuery) . '%';

        return $queryBuilder->where(function (Builder $query) use ($search) {
            foreach ($this->searchableFields() as $field) {
                $this->addSearchCondition($query, $field, $search);
            }
        });
    }

    /**
     * Add a search condition for a specific field.
     * Supports unlimited nested relations (e.g., "author.user.profile.name").
     */
    protected function addSearchCondition(Builder $query, string $field, string $search): void
    {
        $parts = explode('.', $field);

        if (count($parts) === 1) {
            $query->orWhere($parts[0], 'like', $search);
            return;
        }

        $column = array_pop($parts);
        $relationPath = implode('.', $parts);

        $query->orWhereHas($relationPath, function (Builder $q) use ($column, $search) {
            $q->where($column, 'like', $search);
        });
    }

    /**
     * Collect relations to eager-load from searchable fields.
     *
     * @return array<string>
     */
    protected function relationsToEagerLoad(): array
    {
        $relations = [];

        foreach ($this->searchableFields() as $field) {
            $parts = explode('.', $field);
            if (count($parts) > 1) {
                array_pop($parts); // remove column
                $relations[] = implode('.', $parts);
            }
        }

        return array_unique($relations);
    }

    /**
     * Apply sorting to the query.
     */
    protected function applySorting(Builder $queryBuilder): Builder
    {
        return match ($this->sortBy) {
            'oldest' => $queryBuilder->oldest('created_at'),
            default => $queryBuilder->latest('created_at'),
        };
    }

    protected function isValidSortOption(string $option): bool
    {
        return in_array($option, self::VALID_SORT_OPTIONS, true);
    }

    protected function sanitizeSearch(string $query): string
    {
        return preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $query);
    }

    protected function sanitizePerPage(mixed $perPage): int
    {
        $perPage = (int) $perPage;
        return $perPage > 0 ? $perPage : self::DEFAULT_PER_PAGE;
    }
}
