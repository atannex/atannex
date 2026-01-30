<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

interface Reactable
{
    public function isLikedBy(User $user): bool;

    public function isDislikedBy(User $user): bool;

    public function like(User $user): void;

    public function dislike(User $user): void;

    public function removeReaction(User $user): void;
}
