<?php

namespace App\Models\Guards;

use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be filled using mass assignment techniques like `Model::create($attributes)`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * Define the many-to-many relationship with the Role model.
     *
     * This relationship indicates that a permission can belong to multiple roles, and a role can have multiple permissions.
     * It uses the intermediate table and foreign keys configured in the `permission` configuration file.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            config('permission.table_names.role_has_permissions'),
            config('permission.column_names.permission_pivot_key'),
            config('permission.column_names.role_pivot_key')
        );
    }

    /**
     * Define the polymorphic many-to-many relationship with the User model.
     *
     * This relationship allows permissions to be directly assigned to user models (or other models).
     * It uses a polymorphic intermediate table to handle relationships with different model types.
     * The table and column names are sourced from the `permission` configuration file.
     *
     * @return MorphToMany
     */
    public function employee(): MorphToMany
    {
        return $this->morphedByMany(
            Employee::class,
            'model',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.permission_pivot_key'),
            config('permission.column_names.model_morph_key')
        );
    }
}
