<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreInvoiceRequest;
use App\Http\Requests\Api\Admin\UpdateInvoiceRequest;
use App\Http\Requests\Api\Admin\UpdateInvoiceStatusRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\User;
use App\Services\AdminActivityLogger;
use App\Services\InvoiceLineItemSync;
use App\Services\InvoiceNumberService;
use App\Services\InvoicePdfService;
use App\Support\InvoiceStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Invoice::class);

        /** @var User $user */
        $user = $request->user();

        $query = Invoice::query()
            ->with(['client', 'billingUser'])
            ->latest();

        if (! $user->can('view-all-invoices')) {
            $query->where(function ($q) use ($user): void {
                $q->where('created_by_user_id', $user->id)
                    ->orWhere('billing_user_id', $user->id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('client_id')) {
            $query->where('invoice_client_id', $request->integer('client_id'));
        }

        if ($request->boolean('mine') && $user->can('view-all-invoices')) {
            $query->where(function ($q) use ($user): void {
                $q->where('billing_user_id', $user->id)
                    ->orWhere('created_by_user_id', $user->id);
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->string('from')->toString());
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->string('to')->toString());
        }

        return InvoiceResource::collection(
            $query->paginate(perPage: (int) $request->integer('per_page', 20))
        );
    }

    public function assignableUsers(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user?->can('create-invoice') && ! $user?->can('edit-invoice')) {
            abort(403);
        }

        $staff = User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json(['data' => $staff]);
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $billingUserId = $request->integer('billing_user_id') ?: $user->id;
        if ($billingUserId !== $user->id && ! $user->can('edit-invoice')) {
            return response()->json(['message' => 'You cannot assign billing to another user.'], 422);
        }

        if (! User::query()->whereKey($billingUserId)->where('is_active', true)->exists()) {
            return response()->json(['message' => 'Invalid billing user.'], 422);
        }

        $invoice = DB::transaction(function () use ($request, $user, $billingUserId) {
            $invoice = Invoice::create([
                'invoice_client_id' => $request->integer('invoice_client_id'),
                'created_by_user_id' => $user->id,
                'billing_user_id' => $billingUserId,
                'type' => $request->string('type')->toString(),
                'status' => InvoiceStatus::DRAFT,
                'issue_date' => $request->input('issue_date'),
                'due_date' => $request->input('due_date'),
                'currency' => $request->input('currency', 'BDT'),
                'tax_rate' => $request->input('tax_rate', 0),
                'notes_public' => $request->input('notes_public'),
                'notes_internal' => $request->input('notes_internal'),
            ]);

            InvoiceLineItemSync::replace($invoice, $request->input('line_items', []));

            return $invoice;
        });

        AdminActivityLogger::invoice(
            'Invoice draft created '.$invoice->displayNumber(),
            $user,
            $invoice,
            ['invoice_id' => $invoice->id, 'billing_user_id' => $invoice->billing_user_id],
        );

        $invoice->load(['client', 'lineItems', 'billingUser']);

        return response()->json([
            'data' => new InvoiceResource($invoice),
            'message' => 'Draft saved.',
        ], 201);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        $this->authorize('view', $invoice);
        $invoice->load(['client', 'lineItems', 'billingUser']);

        return response()->json(['data' => new InvoiceResource($invoice)]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $data = $request->safe()->except(['line_items']);

        if ($request->has('billing_user_id')) {
            $newBilling = $request->integer('billing_user_id') ?: $invoice->billing_user_id;
            if ($newBilling !== $user->id && ! $user->can('edit-invoice')) {
                return response()->json(['message' => 'You cannot assign billing to another user.'], 422);
            }
            $data['billing_user_id'] = $newBilling;
        }

        $invoice->fill($data);

        if ($request->has('line_items')) {
            InvoiceLineItemSync::replace($invoice, $request->input('line_items', []));
        } else {
            $invoice->save();
        }

        /** @var User $user */
        $user = $request->user();

        AdminActivityLogger::invoice(
            'Invoice draft updated '.$invoice->displayNumber(),
            $user,
            $invoice,
            ['invoice_id' => $invoice->id],
        );

        $invoice->load(['client', 'lineItems', 'billingUser']);

        return response()->json([
            'data' => new InvoiceResource($invoice),
            'message' => 'Draft saved.',
        ]);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $this->authorize('delete', $invoice);

        /** @var User $user */
        $user = request()->user();

        AdminActivityLogger::invoice(
            'Invoice draft deleted '.$invoice->displayNumber(),
            $user,
            $invoice,
            ['invoice_id' => $invoice->id],
        );

        $invoice->delete();

        return response()->json(['message' => 'Draft deleted.']);
    }

    public function send(Request $request, Invoice $invoice, InvoiceNumberService $numbers, InvoicePdfService $pdf): JsonResponse
    {
        $this->authorize('send', $invoice);

        /** @var User $user */
        $user = $request->user();

        $invoice->load('client');

        DB::transaction(function () use ($invoice, $numbers, $pdf, $user): void {
            $issueDate = $invoice->issue_date ?? now();
            $invoice->issue_date = $issueDate;
            $invoice->number = $numbers->assignNextNumber((int) $issueDate->format('Y'));
            $invoice->status = InvoiceStatus::SENT;
            $invoice->sent_at = now();
            $path = $pdf->generate($invoice);
            $invoice->pdf_path = $path;
            $invoice->save();

            Mail::to($invoice->client->email)->queue(new \App\Mail\InvoiceSent($invoice));

            AdminActivityLogger::invoice(
                'Sent invoice '.$invoice->number.' to '.$invoice->client->email,
                $user,
                $invoice,
                [
                    'invoice_id' => $invoice->id,
                    'number' => $invoice->number,
                    'recipient' => $invoice->client->email,
                ],
            );
        });

        $invoice->refresh()->load(['client', 'lineItems', 'billingUser']);

        return response()->json([
            'data' => new InvoiceResource($invoice),
            'message' => 'Invoice sent.',
        ]);
    }

    public function pdf(Invoice $invoice, InvoicePdfService $pdf): \Symfony\Component\HttpFoundation\Response
    {
        $this->authorize('view', $invoice);

        return $pdf->stream($invoice);
    }

    public function updateStatus(UpdateInvoiceStatusRequest $request, Invoice $invoice): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $from = $invoice->status;
        $to = $request->string('status')->toString();

        $invoice->status = $to;
        if ($to === InvoiceStatus::PAID) {
            $invoice->paid_at = now();
        }
        $invoice->save();

        AdminActivityLogger::invoice(
            "Invoice status {$from} → {$to} ({$invoice->displayNumber()})",
            $user,
            $invoice,
            ['invoice_id' => $invoice->id, 'from' => $from, 'to' => $to],
        );

        $invoice->load(['client', 'lineItems', 'billingUser']);

        return response()->json([
            'data' => new InvoiceResource($invoice),
            'message' => 'Status updated.',
        ]);
    }
}
