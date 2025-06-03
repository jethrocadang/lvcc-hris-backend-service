<?php

namespace App\Services\Hris;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getUsers(): Collection
    {
        $users = User::with(['roles', 'permissions'])->get();

        return $users->isNotEmpty()
            ? UserResource::collection($users)->collection
            : collect();
    }

    public function deleteUser(int $id): void
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('User not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('User deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Attach and detach roles and permissions
     */

    public function attachRole(User $user, string $roleName): bool
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        // Remove existing roles to ensure only one role is assigned to user
        $user->syncRoles([$role]);

        return true;
    }

    public function detachRole(User $user, string $roleName): bool
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        $user->removeRole($role);
        return true;
    }

    public function attachPermissions(User $user, array $permissions): bool
    {
        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->firstOrFail();
            $user->givePermissionTo($permission);
        }

        return true;
    }

    public function detachPermissions(User $user, array $permissions): bool
    {
        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->firstOrFail();
            $user->revokePermissionTo($permission);
        }

        return true;
    }
}
