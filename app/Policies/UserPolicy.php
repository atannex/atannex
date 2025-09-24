<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        return true;
    }

    protected function hasAccess(User $user, string $permission, array|string $roles): bool
    {
        $roles = (array) $roles;
        return $user->hasAnyRole($roles) && $user->hasPermissionTo($permission);
    }

    public function viewAny(User $user): bool
    {
        return $this->hasAccess($user, 'view users', ['Admin', 'Editor', 'Auditor']);
    }

    public function view(User $user, User $model): bool
    {
        return (
            $this->hasAccess($user, 'view users', ['Admin', 'Editor', 'Auditor']) ||
            $user->id === $model->id
        );
    }

    public function create(User $user): bool
    {
        return $this->hasAccess($user, 'create users', ['Admin']);
    }

    public function update(User $user, User $model): bool
    {
        return $this->hasAccess($user, 'edit users', ['Admin']) && $user->id !== $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $this->hasAccess($user, 'delete users', ['Admin']) && $user->id !== $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return $this->hasAccess($user, 'restore users', ['Admin']);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $this->hasAccess($user, 'force delete users', ['Admin']);
    }

    public function assignRoles(User $user): bool
    {
        return $this->hasAccess($user, 'assign roles', ['Admin']);
    }

    public function managePermissions(User $user, User $model): bool
    {
        return $this->hasAccess($user, 'manage user permissions', ['Admin']) && $user->id !== $model->id;
    }
}
