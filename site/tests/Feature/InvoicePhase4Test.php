<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\InvoiceClient;
use App\Models\User;
use App\Support\InvoiceStatus;
use Database\Seeders\PermissionCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InvoicePhase4Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionCatalogSeeder::class);
    }

    private function userWith(array $permissions): User
    {
        $role = Role::create(['name' => 'billing-'.uniqid(), 'guard_name' => 'web']);
        $role->syncPermissions($permissions);
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole($role);

        return $user;
    }

    private function client(User $creator): InvoiceClient
    {
        return InvoiceClient::create([
            'company_name' => 'Pharma Co',
            'email' => 'billing@pharma.test',
            'created_by_user_id' => $creator->id,
        ]);
    }

    /** @return array<string, mixed> */
    private function draftPayload(int $clientId, int $billingUserId): array
    {
        return [
            'invoice_client_id' => $clientId,
            'billing_user_id' => $billingUserId,
            'type' => 'quotation',
            'tax_rate' => 10,
            'line_items' => [
                ['description' => 'Equipment supply', 'quantity' => 2, 'unit_price' => 1000],
            ],
        ];
    }

    public function test_view_own_cannot_see_other_billing_users_invoice(): void
    {
        $owner = $this->userWith(['create-invoice', 'view-own-invoices']);
        $other = $this->userWith(['create-invoice', 'view-own-invoices']);
        $client = $this->client($owner);

        $invoice = Invoice::create([
            'invoice_client_id' => $client->id,
            'created_by_user_id' => $other->id,
            'billing_user_id' => $other->id,
            'type' => 'quotation',
            'status' => InvoiceStatus::DRAFT,
            'currency' => 'BDT',
            'subtotal' => 100,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'total' => 100,
        ]);

        $this->actingAs($owner)
            ->getJson("/api/admin/invoices/{$invoice->id}")
            ->assertForbidden();
    }

    public function test_billing_assignee_can_view_invoice(): void
    {
        $creator = $this->userWith(['create-invoice', 'edit-invoice', 'view-own-invoices']);
        $assignee = $this->userWith(['view-own-invoices']);
        $client = $this->client($creator);

        $invoice = Invoice::create([
            'invoice_client_id' => $client->id,
            'created_by_user_id' => $creator->id,
            'billing_user_id' => $assignee->id,
            'type' => 'quotation',
            'status' => InvoiceStatus::DRAFT,
            'currency' => 'BDT',
            'subtotal' => 100,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'total' => 100,
        ]);

        $this->actingAs($assignee)
            ->getJson("/api/admin/invoices/{$invoice->id}")
            ->assertOk();
    }

    public function test_send_assigns_unique_number_and_logs_activity(): void
    {
        Mail::fake();

        $user = $this->userWith([
            'manage-invoice-clients',
            'create-invoice',
            'edit-invoice',
            'send-invoice',
            'view-all-invoices',
        ]);
        $client = $this->client($user);

        $create = $this->actingAs($user)
            ->postJson('/api/admin/invoices', $this->draftPayload($client->id, $user->id))
            ->assertCreated();

        $id = $create->json('data.id');

        $this->actingAs($user)
            ->postJson("/api/admin/invoices/{$id}/send")
            ->assertOk()
            ->assertJsonPath('data.status', InvoiceStatus::SENT);

        $invoice = Invoice::findOrFail($id);
        $this->assertMatchesRegularExpression('/^ENV-\d{4}-\d{4}$/', $invoice->number ?? '');

        Mail::assertQueued(\App\Mail\InvoiceSent::class);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'invoice',
            'description' => 'Sent invoice '.$invoice->number.' to billing@pharma.test',
        ]);
    }

    public function test_viewer_without_invoice_permission_gets_403(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($user)
            ->getJson('/api/admin/invoices')
            ->assertForbidden();
    }
}
