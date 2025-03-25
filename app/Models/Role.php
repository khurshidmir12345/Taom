<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name'];

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /**
     * Sync permissions to the role.
     *
     * @param array $permissionIds
     * @return void
     */
    public function syncPermissions(array $permissionIds): void
    {
        // First detach all permissions
        $this->permissions()->detach();

        // Then attach the new permissions
        foreach ($permissionIds as $id) {
            DB::table('permission_role')->insert([
                'permission_id' => $id,
                'role_id' => $this->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Check if the role has a specific permission.
     *
     * @param string|int $permission Permission ID or name
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if (is_numeric($permission)) {
            return $this->permissions->contains('id', $permission);
        }

        return $this->permissions->contains('name', $permission);
    }

    /**
     * Debug method to check permission_role table
     */
    public static function debugPermissionRole()
    {
        return DB::table('permission_role')->get();
    }
}

