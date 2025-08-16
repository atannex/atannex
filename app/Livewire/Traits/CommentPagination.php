<?php

namespace App\Livewire\Traits;

use Livewire\WithPagination;

trait CommentPagination
{
    use WithPagination;

    public int $perPage = 10;

    public function loadMoreComments(): void
    {
        $this->perPage += 10;
    }
}
