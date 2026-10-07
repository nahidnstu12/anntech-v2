<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\User;
use App\Support\InquiryStatus;
use Database\Seeders\PermissionCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ContactInquiryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionCatalogSeeder::class);
    }

    private function staffWithPermissions(array $permissions): User
    {
        $role = Role::create(['name' => 'ops', 'guard_name' => 'web']);
        $role->syncPermissions($permissions);

        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole($role);

        return $user;
    }

    public function test_contact_form_persists_inquiry_and_queues_mail(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $this->from('/')
            ->post('/contact', [
                'name' => 'Amina Rahman',
                'email' => 'amina@example.com',
                'phone' => '+880 1700 000000',
                'message' => 'Need an AHU package quote.',
            ])
            ->assertRedirect('/#contact');

        $this->assertDatabaseCount('contact_inquiries', 1);
        $inquiry = ContactInquiry::firstOrFail();
        $this->assertSame(InquiryStatus::NEW, $inquiry->status);

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\ContactEnquiry::class);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'inquiry',
            'description' => 'Inquiry received',
        ]);
    }

    public function test_staff_without_permission_cannot_list_inquiries(): void
    {
        ContactInquiry::create([
            'name' => 'Test',
            'email' => 't@example.com',
            'phone' => '1',
            'message' => 'Hi',
            'status' => InquiryStatus::NEW,
        ]);

        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($user)
            ->getJson('/api/admin/contact-inquiries')
            ->assertForbidden();
    }

    public function test_viewer_can_list_and_open_detail_sets_read_at(): void
    {
        $inquiry = ContactInquiry::create([
            'name' => 'Test',
            'email' => 't@example.com',
            'phone' => '1',
            'message' => 'Hello there',
            'status' => InquiryStatus::NEW,
        ]);

        $viewer = $this->staffWithPermissions(['view-contact-inquiries']);

        $this->actingAs($viewer)
            ->getJson('/api/admin/contact-inquiries')
            ->assertOk()
            ->assertJsonPath('data.0.id', $inquiry->id);

        $this->actingAs($viewer)
            ->getJson("/api/admin/contact-inquiries/{$inquiry->id}")
            ->assertOk();

        $this->assertNotNull($inquiry->fresh()->read_at);
    }

    public function test_viewer_cannot_patch_inquiry(): void
    {
        $inquiry = ContactInquiry::create([
            'name' => 'Test',
            'email' => 't@example.com',
            'phone' => '1',
            'message' => 'Hello',
            'status' => InquiryStatus::NEW,
        ]);

        $viewer = $this->staffWithPermissions(['view-contact-inquiries']);

        $this->actingAs($viewer)
            ->patchJson("/api/admin/contact-inquiries/{$inquiry->id}", [
                'status' => InquiryStatus::CLOSED,
            ])
            ->assertForbidden();
    }

    public function test_manager_can_update_status_and_logs_activity(): void
    {
        $inquiry = ContactInquiry::create([
            'name' => 'Test',
            'email' => 't@example.com',
            'phone' => '1',
            'message' => 'Hello',
            'status' => InquiryStatus::NEW,
        ]);

        $manager = $this->staffWithPermissions(['manage-contact-inquiries']);

        $this->actingAs($manager)
            ->patchJson("/api/admin/contact-inquiries/{$inquiry->id}", [
                'status' => InquiryStatus::IN_PROGRESS,
                'internal_notes' => 'Called back.',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', InquiryStatus::IN_PROGRESS);

        $this->assertTrue(
            Activity::where('log_name', 'inquiry')
                ->where('description', 'Inquiry status changed')
                ->exists()
        );
    }

    public function test_summary_returns_new_count_for_viewers(): void
    {
        ContactInquiry::create([
            'name' => 'A',
            'email' => 'a@t.com',
            'phone' => '1',
            'message' => 'm',
            'status' => InquiryStatus::NEW,
        ]);
        ContactInquiry::create([
            'name' => 'B',
            'email' => 'b@t.com',
            'phone' => '2',
            'message' => 'm',
            'status' => InquiryStatus::CLOSED,
        ]);

        $viewer = $this->staffWithPermissions(['view-contact-inquiries']);

        $this->actingAs($viewer)
            ->getJson('/api/admin/summary')
            ->assertOk()
            ->assertJsonPath('data.new_inquiries', 1);
    }
}
