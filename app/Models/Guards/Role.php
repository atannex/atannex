<?php

namespace App\Models\Guards;

use App\Models\Regions\Employee;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Role extends SpatieRole
{
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
     * Define the many-to-many relationship with the Permission model.
     *
     * This relationship indicates that a role can have multiple permissions, and a permission can be assigned to multiple roles.
     * It utilizes the intermediate table and foreign key configurations specified in the `permission` configuration file.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            config('permission.table_names.role_has_permissions'),
            config('permission.column_names.role_pivot_key'),
            config('permission.column_names.permission_pivot_key')
        );
    }

    /**
     * Define the polymorphic many-to-many relationship with the User model.
     *
     * This relationship enables direct assignment of roles to user models (or other models).
     * It employs a polymorphic intermediate table to accommodate relationships with various model types.
     * The table and column names are retrieved from the `permission` configuration file.
     */
    public function employee(): MorphToMany
    {
        return $this->morphedByMany(
            Employee::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.role_pivot_key'),
            config('permission.column_names.model_morph_key')
        );
    }
}
