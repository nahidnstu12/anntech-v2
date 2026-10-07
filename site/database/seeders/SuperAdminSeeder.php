<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\AdminRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role = Role::firstOrCreate([
            'name' => AdminRoles::SUPER_ADMIN,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(Permission::where('guard_name', 'web')->pluck('name'));

        $email = config('admin.super_admin_email') ?: 'superadmin@enovak.test';
        $password = config('admin.super_admin_password') ?: 'ChangeMeNow!123';

        $user = User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => config('admin.super_admin_name'),
                'password' => $password,
                'is_active' => true,
            ],
        );

        if (! $user->hasRole($role)) {
            $user->syncRoles([$role]);
        }
    }
}
