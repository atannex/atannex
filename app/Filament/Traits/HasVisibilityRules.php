<?php

namespace App\Filament\Traits;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

trait HasVisibilityRules
{
    /**
     * Resolve the current authenticated user safely.
     */
    protected static function currentUser(): ?User
    {
        /** @var Authenticatable|User|null $user */
        $user = Filament::auth()?->user() ?? Auth::user();

        return $user instanceof User ? $user : null;
    }

    /**
     * Generic permission checker.
     *
     * Accepts single or multiple permissions.
     */
    protected static function can(string|array $permissions): bool
    {
        $user = static::currentUser();

        if (! $user || ! method_exists($user, 'can')) {
            return false;
        }

        foreach ((array) $permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * COMMENT moderation visibility
     */
    protected static function canModerateComments(): bool
    {
        return static::can([
            'comments.moderate',
            'comments.edit.any',
            'comments.delete.any',
            'comments.view.system_data',
        ]);
    }

    /**
     * POST moderation visibility
     */
    protected static function canModeratePosts(): bool
    {
        return static::can([
            'posts.moderate',
            'posts.edit.any',
            'posts.delete.any',
            'posts.publish',
        ]);
    }

    /**
     * Unified moderation visibility (used in tables & forms)
     */
    protected static function canSeeModerationContent(): bool
    {
        return static::canModerateComments()
            || static::canModeratePosts();
    }

    /**
     * Common Filament helper
     */
    protected static function hideFromRegularUsers(): bool
    {
        return ! static::canSeeModerationContent();
    }
}
