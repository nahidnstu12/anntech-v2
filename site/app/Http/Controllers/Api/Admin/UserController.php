<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreUserRequest;
use App\Http\Requests\Api\Admin\UpdateUserRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\User;
use App\Services\AdminActivityLogger;
use App\Support\AdminRoles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate(perPage: (int) $request->integer('per_page', 15));

        return AdminUserResource::collection($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $role = Role::findOrFail($request->integer('role_id'));

        $user = User::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->lower()->toString(),
            'password' => $request->string('password')->toString(),
            'phone' => $request->input('phone'),
            'job_title' => $request->input('job_title'),
            'is_active' => true,
        ]);

        $user->syncRoles([$role]);
        $user->load('roles');

        AdminActivityLogger::user('User created', $actor, $user);

        return response()->json([
            'data' => new AdminUserResource($user),
            'message' => 'User created.',
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        $user->load('roles');

        return response()->json(['data' => new AdminUserResource($user)]);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        if ($user->isProtectedSuperAdmin()) {
            if ($request->has('is_active') && ! $request->boolean('is_active')) {
                return response()->json(['message' => 'The super admin account cannot be deactivated.'], 422);
            }
        }

        if ($request->has('is_active') && ! $request->boolean('is_active') && $actor->is($user)) {
            return response()->json(['message' => 'You cannot deactivate your own account.'], 422);
        }

        $user->fill($request->safe()->only(['name', 'email', 'phone', 'job_title']));

        if ($request->filled('password')) {
            $user->password = $request->string('password')->toString();
            $user->revokeAllSessions();
        }

        if ($request->has('is_active')) {
            $user->is_active = $request->boolean('is_active');
        }

        $user->save();

        if ($request->has('role_id')) {
            $role = Role::findOrFail($request->integer('role_id'));
            if ($role->name === AdminRoles::SUPER_ADMIN) {
                return response()->json(['message' => 'The super admin role cannot be assigned.'], 422);
            }
            $user->syncRoles([$role]);
        }

        if ($request->has('is_active') && ! $user->is_active) {
            $user->revokeAllSessions();
            AdminActivityLogger::user('User deactivated', $actor, $user);
        } else {
            AdminActivityLogger::user('User updated', $actor, $user);
        }

        $user->load('roles');

        return response()->json([
            'data' => new AdminUserResource($user),
            'message' => 'User updated.',
        ]);
    }
}
