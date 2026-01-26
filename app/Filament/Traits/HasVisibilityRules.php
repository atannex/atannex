<?php

namespace App\Filament\Traits;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

trait HasVisibilityRules
{
    /**
     * Determine if the current user can moderate content.
     *
     * Works across Filament panels, HTTP, CLI, and queues.
     */
    protected static function isModerator(): bool
    {
        /** @var Authenticatable|User|null $user */
        $user = Filament::auth()?->user() ?? Auth::user();

        return $user instanceof User
            && method_exists($user, 'hasAnyRole')
            && $user->hasAnyRole(
                [
                    'Administrator',
                    'Super Administrator'
                ]
            );
    }

    /**
     * Semantic alias for UI visibility rules.
     */
    protected static function canSeeModerationContent(): bool
    {
        return static::isModerator();
    }

    /**
     * Common Filament visibility helper.
     */
    protected static function hideFromRegularUsers(): bool
    {
        return ! static::isModerator();
    }
}
