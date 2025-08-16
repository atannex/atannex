<?php

namespace App\Livewire\Traits;

use App\Models\Comments\Comment as CommentModel;
use Illuminate\Support\Facades\Auth;

trait CommentAuthorization
{
    protected function isAuthorized(CommentModel $comment): bool
    {
        return Auth::id() === $comment->user_id;
    }
}
