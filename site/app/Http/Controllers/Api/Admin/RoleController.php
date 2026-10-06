<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreRoleRequest;
use App\Http\Requests\Api\Admin\SyncRolePermissionsRequest;
use App\Http\Requests\Api\Admin\UpdateRoleRequest;
use App\Http\Resources\AdminRoleResource;
use App\Models\User;
use App\Services\AdminActivityLogger;
use App\Support\AdminPermissions;
use App\Support\AdminRoles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $roles = Role::query()
            ->where('guard_name', 'web')
            ->with('permissions')
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return AdminRoleResource::collection($roles);
    }

    public function options(): JsonResponse
    {
        $roles = Role::query()
            ->where('guard_name', 'web')
            ->where('name', '!=', AdminRoles::SUPER_ADMIN)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'data' => $roles,
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $role = Role::create([
            'name' => $request->string('name')->toString(),
            'guard_name' => 'web',
        ]);

        AdminActivityLogger::role('Role created', $actor, [
            'role_id' => $role->id,
            'name' => $role->name,
        ]);

        return response()->json([
            'data' => new AdminRoleResource($role->load('permissions')),
            'message' => 'Role created.',
        ], 201);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        if ($role->name === AdminRoles::SUPER_ADMIN) {
            return response()->json(['message' => 'The super admin role cannot be modified.'], 422);
        }

        /** @var User $actor */
        $actor = $request->user();

        $role->update($request->validated());
        $role->load('permissions');

        AdminActivityLogger::role('Role updated', $actor, [
            'role_id' => $role->id,
            'name' => $role->name,
        ]);

        return response()->json([
            'data' => new AdminRoleResource($role),
            'message' => 'Role updated.',
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        if ($role->name === AdminRoles::SUPER_ADMIN) {
            return response()->json(['message' => 'The super admin role cannot be deleted.'], 422);
        }

        if ($role->users()->count() > 0) {
            return response()->json(['message' => 'Remove users from this role before deleting.'], 422);
        }

        /** @var User $actor */
        $actor = request()->user();

        AdminActivityLogger::role('Role deleted', $actor, [
            'role_id' => $role->id,
            'name' => $role->name,
        ]);

        $role->delete();

        return response()->json(['message' => 'Role deleted.']);
    }

    public function syncPermissions(SyncRolePermissionsRequest $request, Role $role): JsonResponse
    {
        if ($role->name === AdminRoles::SUPER_ADMIN) {
            return response()->json(['message' => 'Super admin permissions are fixed.'], 422);
        }

        /** @var User $actor */
        $actor = $request->user();

        $names = AdminPermissions::stripSuperAdminOnly($request->input('permissions', []));

        $role->syncPermissions($names);
        $role->load('permissions');

        AdminActivityLogger::role('Permissions synced on role', $actor, [
            'role_id' => $role->id,
            'permission_names' => $names,
        ]);

        return response()->json([
            'data' => new AdminRoleResource($role),
            'message' => 'Permissions updated.',
        ]);
    }
}
