<?php

namespace Tests\Feature;

use App\Models\ApplicationErrorLog;
use App\Models\User;
use App\Support\AdminRoles;
use Database\Seeders\PermissionCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApplicationErrorLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionCatalogSeeder::class);
    }

    private function makeSuperAdmin(): User
    {
        $role = Role::create(['name' => AdminRoles::SUPER_ADMIN, 'guard_name' => 'web']);
        $role->syncPermissions(Permission::pluck('name'));

        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole($role);

        return $user;
    }

    public function test_server_error_is_persisted_with_request_id_header(): void
    {
        $this->getJson('/api/admin/_test/throw', [
            'X-Request-Id' => 'test-correlation-uuid',
        ])->assertStatus(500);

        $this->assertDatabaseHas('application_error_logs', [
            'message' => 'Recorded test failure',
            'request_id' => 'test-correlation-uuid',
            'status_code' => 500,
        ]);
    }

    public function test_not_found_is_not_persisted(): void
    {
        $before = ApplicationErrorLog::count();

        $this->getJson('/api/admin/no-such-route')->assertNotFound();

        $this->assertSame($before, ApplicationErrorLog::count());
    }

    public function test_super_admin_can_list_and_show_error_logs(): void
    {
        ApplicationErrorLog::create([
            'level' => 'error',
            'exception_class' => \RuntimeException::class,
            'message' => 'Sample',
            'file' => '/app/foo.php',
            'line' => 1,
            'stack_trace' => "#0 /app/foo.php(1)\n",
            'request_id' => 'abc',
            'status_code' => 500,
            'context' => ['input' => []],
            'created_at' => now(),
        ]);

        $super = $this->makeSuperAdmin();

        $this->actingAs($super)
            ->getJson('/api/admin/error-logs')
            ->assertOk()
            ->assertJsonPath('data.0.message', 'Sample')
            ->assertJsonMissingPath('data.0.stack_trace');

        $id = ApplicationErrorLog::first()->id;

        $this->actingAs($super)
            ->getJson("/api/admin/error-logs/{$id}")
            ->assertOk()
            ->assertJsonPath('data.stack_trace', "#0 /app/foo.php(1)\n")
            ->assertJsonPath('data.context.input', []);
    }

    public function test_error_log_api_requires_super_admin(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($user)
            ->getJson('/api/admin/error-logs')
            ->assertForbidden();
    }
}
