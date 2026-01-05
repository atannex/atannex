<?php

namespace App\Livewire\Search\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

abstract class Searchable extends Component
{
    use WithPagination;

    /** --------------------
     *  Public state
     *  --------------------
     */
    public string $query = '';
    public int $perPage = 6;
    public string $sortBy = self::DEFAULT_SORT;

    /** --------------------
     *  Configuration
     *  --------------------
     */
    protected const VALID_SORT_OPTIONS = ['latest', 'oldest'];
    protected const DEFAULT_SORT = 'latest';
    protected const DEFAULT_PER_PAGE = 6;

    /** --------------------
     *  Abstract contracts
     *  --------------------
     */
    abstract protected function newModelQuery(): Builder;

    abstract protected function view(): string;

    /**
     * @return array<string>
     */
    abstract protected function searchableFields(): array;

    /** --------------------
     *  Lifecycle hooks
     *  --------------------
     */
    public function updating(string $name): void
    {
        if (in_array($name, ['query', 'sortBy', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    /** --------------------
     *  Rendering
     *  --------------------
     */
    public function render(): View
    {
        $query = $this->applyFilters(
            $this->newModelQuery()
        );

        return view($this->view(), [
            'items' => $query->paginate(
                $this->sanitizePerPage($this->perPage)
            ),
        ]);
    }

    /** --------------------
     *  Query composition
     *  --------------------
     */
    protected function applyFilters(Builder $query): Builder
    {
        $this->applySearch($query);
        $this->applySorting($query);

        return $query;
    }

    /**
     * Adds a where-group to the provided query that filters records by matching the component's
     * search string against the configured searchable fields.
     *
     * Trims the component search string and leaves the query unchanged if the trimmed value is
     * empty or the string "0". Otherwise, applies a grouped condition that checks for the search
     * term across each searchable field (including related fields expressed via dot notation).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder to modify.
     */
    protected function applySearch(Builder $query): void
    {
        $search = trim($this->query);

        if ($search === '' || $search === '0') {
            return;
        }

        $like = '%' . $search . '%';
        $fields = $this->searchableFields();

        $query->where(function (Builder $q) use ($fields, $like) {
            foreach ($fields as $index => $field) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $this->addSearchCondition($q, $field, $like, $method);
            }
        });
    }

    /**
     * Adds a search condition for a field to the given query, supporting related fields via dot notation.
     *
     * For a top-level field, applies the provided `$method` (`where` or `orWhere`) with a SQL `LIKE`
     * comparison against `$search`. For a nested field (relation.column), applies the corresponding
     * relation existence condition (`whereHas` / `orWhereHas`) and filters the related records by
     * the column using a `LIKE` comparison with `$search`.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder to modify.
     * @param string $field Field name to search or relation path in dot notation (e.g. `author.name`).
     * @param string $search The `LIKE` pattern to match (should include `%` wildcards as needed).
     * @param string $method The base method to use for top-level conditions (`where` or `orWhere`).
     */
    protected function addSearchCondition(
        Builder $query,
        string $field,
        string $search,
        string $method
    ): void {
        $parts = explode('.', $field);

        if (count($parts) === 1) {
            $query->{$method}($parts[0], 'like', $search);
            return;
        }

        $column = array_pop($parts);
        $relation = implode('.', $parts);

        $query->{$method . 'Has'}($relation, function (Builder $q) use ($column, $search): void {
            $q->where($column, 'like', $search);
        });
    }

    /**
     * Apply the configured sort order to the provided query based on $this->sortBy.
     *
     * Orders by `created_at` ascending when `sortBy` is 'oldest', descending when `sortBy` is 'latest'.
     * If `sortBy` is not a valid option, falls back to the class default.
     *
     * @param Builder $query The Eloquent query builder to modify.
     */
    protected function applySorting(Builder $query): void
    {
        $sort = in_array($this->sortBy, self::VALID_SORT_OPTIONS, true)
            ? $this->sortBy
            : self::DEFAULT_SORT;

        match ($sort) {
            'oldest' => $query->oldest('created_at'),
            default  => $query->latest('created_at'),
        };
    }

    /**
     * Normalize the requested items-per-page value to a positive integer.
     *
     * Casts the provided value to an integer and returns it when greater than zero;
     * otherwise returns self::DEFAULT_PER_PAGE.
     *
     * @param mixed $perPage The requested items-per-page value.
     * @return int The validated per-page count; `self::DEFAULT_PER_PAGE` if the input is less than or equal to zero.
     */
    protected function sanitizePerPage(mixed $perPage): int
    {
        $perPage = (int) $perPage;

        return $perPage > 0
            ? $perPage
            : self::DEFAULT_PER_PAGE;
    }
}