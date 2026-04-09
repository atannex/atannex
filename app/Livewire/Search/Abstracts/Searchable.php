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

    public int $perPage = 6;

    public string $sortBy = self::DEFAULT_SORT;

    protected const VALID_SORT_OPTIONS = ['latest', 'oldest'];

    protected const DEFAULT_SORT = 'latest';

    protected const DEFAULT_PER_PAGE = 6;

    abstract protected function newModelQuery(): Builder;

    abstract protected function view(): string;


    abstract protected function searchableFields(): array;


    public function updating(string $name): void
    {
        if (in_array($name, ['query', 'sortBy', 'perPage'], true)) {
            $this->resetPage();
        }
    }

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

    protected function applyFilters(Builder $query): Builder
    {
        $this->applySearch($query);
        $this->applySorting($query);

        return $query;
    }

    protected function applySearch(Builder $query): void
    {
        $search = trim($this->query);

        if ($search === '' || $search === '0') {
            return;
        }

        $like = '%'.$search.'%';
        $fields = $this->searchableFields();

        $query->where(function (Builder $q) use ($fields, $like) {
            foreach ($fields as $index => $field) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $this->addSearchCondition($q, $field, $like, $method);
            }
        });
    }


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

        $query->{$method.'Has'}($relation, function (Builder $q) use ($column, $search): void {
            $q->where($column, 'like', $search);
        });
    }

    protected function applySorting(Builder $query): void
    {
        $sort = in_array($this->sortBy, self::VALID_SORT_OPTIONS, true)
            ? $this->sortBy
            : self::DEFAULT_SORT;

        match ($sort) {
            'oldest' => $query->oldest('created_at'),
            default => $query->latest('created_at'),
        };
    }

    protected function sanitizePerPage(mixed $perPage): int
    {
        $perPage = (int) $perPage;

        return $perPage > 0
            ? $perPage
            : self::DEFAULT_PER_PAGE;
    }
}
