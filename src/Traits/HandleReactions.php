<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Contracts\Reactable;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

trait HandleReactions
{
    protected function handleReaction(
        string $modelClass,
        int $modelId,
        string $type
    ): void {
        $user = Auth::user() ?? abort(403, 'Authentication required.');

        /** @var Model&Reactable $model */
        $model = $modelClass::query()->findOrFail($modelId);

        Gate::authorize('react', $model);

        $this->toggleReaction($model, $user, $type);

        if (method_exists($this, 'dispatch')) {
            $this->dispatch('$refresh');
        }
    }

    protected function toggleReaction(
        Reactable $model,
        User $user,
        string $type
    ): void {
        DB::transaction(function () use ($model, $user, $type): void {
            if ($this->alreadyReacted($model, $user, $type)) {
                $model->removeReaction($user);
                return;
            }

            $this->applyReaction($model, $user, $type);
        });
    }

    private function alreadyReacted(
        Reactable $model,
        User $user,
        string $type
    ): bool {
        return match ($type) {
            'like'    => $model->isLikedBy($user),
            'dislike' => $model->isDislikedBy($user),
            default   => abort(422, 'Invalid reaction type.'),
        };
    }

    private function applyReaction(
        Reactable $model,
        User $user,
        string $type
    ): void {
        match ($type) {
            'like'    => $model->like($user),
            'dislike' => $model->dislike($user),
        };
    }
}
