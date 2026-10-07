<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\AdminPermissions;
use App\Support\AdminRoles;
use Database\Seeders\PermissionCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPhase2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionCatalogSeeder::class);
    }

    /** @return array<string, string> */
    private function statefulHeaders(): array
    {
        return [
            'Origin' => 'http://localhost',
            'Referer' => 'http://localhost/admin/login',
        ];
    }

    private function makeSuperAdmin(): User
    {
        $role = Role::create(['name' => AdminRoles::SUPER_ADMIN, 'guard_name' => 'web']);
        $role->syncPermissions(Permission::pluck('name'));

        config(['admin.super_admin_email' => 'super@enovak.test']);

        $user = User::factory()->create([
            'email' => 'super@enovak.test',
            'password' => 'Password1!',
            'is_active' => true,
        ]);
        $user->assignRole($role);

        return $user;
    }

    public function test_guest_cannot_access_me(): void
    {
        $this->getJson('/api/admin/me')->assertUnauthorized();
    }

    public function test_login_and_me_flow(): void
    {
        $this->makeSuperAdmin();

        $this->withHeaders($this->statefulHeaders())
            ->postJson('/api/admin/login', [
                'email' => 'super@enovak.test',
                'password' => 'Password1!',
            ])->assertOk()->assertJsonPath('data.email', 'super@enovak.test');

        $this->withHeaders($this->statefulHeaders())
            ->getJson('/api/admin/me')
            ->assertOk()
            ->assertJsonPath('data.is_super_admin', true);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = $this->makeSuperAdmin();
        $user->update(['is_active' => false]);

        $this->withHeaders($this->statefulHeaders())
            ->postJson('/api/admin/login', [
                'email' => 'super@enovak.test',
                'password' => 'Password1!',
            ])->assertUnprocessable();
    }

    public function test_staff_without_manage_users_gets_403_on_users(): void
    {
        $role = Role::create(['name' => 'staff', 'guard_name' => 'web']);
        $role->givePermissionTo('view-contact-inquiries');

        $staff = User::factory()->create(['is_active' => true]);
        $staff->assignRole($role);

        $this->actingAs($staff)->getJson('/api/admin/users')->assertForbidden();
    }

    public function test_super_admin_creates_role_and_staff_user(): void
    {
        $super = $this->makeSuperAdmin();

        $this->actingAs($super)
            ->postJson('/api/admin/roles', ['name' => 'admin'])
            ->assertCreated();

        $role = Role::where('name', 'admin')->firstOrFail();
        $role->syncPermissions([AdminPermissions::MANAGE_USERS]);

        $this->actingAs($super)
            ->postJson('/api/admin/users', [
                'name' => 'Staff One',
                'email' => 'staff@enovak.test',
                'password' => 'Password1!',
                'role_id' => $role->id,
            ])
            ->assertCreated();

        $staff = User::where('email', 'staff@enovak.test')->firstOrFail();
        $this->assertTrue($staff->hasRole('admin'));
    }

    public function test_cannot_assign_super_admin_role_to_staff(): void
    {
        $super = $this->makeSuperAdmin();
        $superRole = Role::where('name', AdminRoles::SUPER_ADMIN)->firstOrFail();

        $this->actingAs($super)
            ->postJson('/api/admin/users', [
                'name' => 'Bad',
                'email' => 'bad@enovak.test',
                'password' => 'Password1!',
                'role_id' => $superRole->id,
            ])
            ->assertUnprocessable();
    }

    public function test_role_sync_strips_super_admin_only_permissions(): void
    {
        $super = $this->makeSuperAdmin();

        $role = Role::create(['name' => 'ops', 'guard_name' => 'web']);

        $this->actingAs($super)
            ->putJson("/api/admin/roles/{$role->id}/permissions", [
                'permissions' => [
                    AdminPermissions::MANAGE_USERS,
                    AdminPermissions::MANAGE_ROLES,
                    AdminPermissions::VIEW_ACTIVITY_LOG,
                ],
            ])
            ->assertOk();

        $role->refresh();
        $this->assertTrue($role->hasPermissionTo(AdminPermissions::MANAGE_USERS));
        $this->assertFalse($role->hasPermissionTo(AdminPermissions::MANAGE_ROLES));
        $this->assertFalse($role->hasPermissionTo(AdminPermissions::VIEW_ACTIVITY_LOG));
    }

    public function test_activity_log_requires_super_admin(): void
    {
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo(AdminPermissions::VIEW_ACTIVITY_LOG);

        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole($role);

        $this->actingAs($user)->getJson('/api/admin/activity')->assertForbidden();
    }

    public function test_change_password_logs_out(): void
    {
        $super = $this->makeSuperAdmin();

        $this->withHeaders($this->statefulHeaders())
            ->actingAs($super)
            ->putJson('/api/admin/me/password', [
                'current_password' => 'Password1!',
                'password' => 'NewPassword1!',
                'password_confirmation' => 'NewPassword1!',
            ])
            ->assertOk();

        $this->assertTrue(Hash::check('NewPassword1!', $super->fresh()->password));
        $this->assertFalse(Hash::check('Password1!', $super->fresh()->password));
    }
}
