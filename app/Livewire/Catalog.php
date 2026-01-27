<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Posts\Video;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Regions\Employee;

class Catalog extends Component
{
    use WithPagination;

    public $filters = [
        'category' => 0,
        'region'   => 0,
        'author'   => 0,
        'duration' => 0,
        'sort'     => 'latest',
    ];

    protected $queryString = [
        'filters.category' => ['except' => 0],
        'filters.region'   => ['except' => 0],
        'filters.author'   => ['except' => 0],
        'filters.duration' => ['except' => 0],
        'filters.sort'     => ['except' => 'latest'],
    ];

    /**
     * Triggered only when user clicks Apply
     */
    public function applyFilters(): void
    {
        $this->resetPage();
    }

    /**
     * Apply dynamic filters to the Video query
     */
    protected function applyFiltersToQuery($query)
    {
        foreach ($this->filters as $key => $value) {
            if (!$value || $value === 0) continue;

            match ($key) {
                'category' => $query->where('category_id', $value),
                'region'   => $query->where('region_id', $value),
                'author'   => $query->where('author_id', $value),
                'duration' => $query->where('duration', '<=', (int) $value),
                'sort'     => match ($value) {
                    'oldest' => $query->oldest('published_at'),
                    'title'  => $query->orderBy('title'),
                    default  => $query->latest('published_at'),
                },
            };
        }

        return $query;
    }

    public function render()
    {
        $query = Video::published()->with(['category', 'author', 'region']);
        $query = $this->applyFiltersToQuery($query);

        $total = (clone $query)->count();

        return view('livewire.catalog', [
            'videos'     => $query->paginate(18),
            'total'      => $total,
            'categories' => Category::orderBy('name')->get(),
            'regions'    => Region::orderBy('name')->get(),
            'authors'    => Employee::with('user')->orderBy('created_at')->get(),
        ]);
    }
}
