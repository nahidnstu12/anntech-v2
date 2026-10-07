<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreInvoiceClientRequest;
use App\Http\Requests\Api\Admin\UpdateInvoiceClientRequest;
use App\Http\Resources\InvoiceClientResource;
use App\Models\InvoiceClient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvoiceClientController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', InvoiceClient::class);

        $clients = InvoiceClient::query()
            ->orderBy('company_name')
            ->paginate(perPage: (int) $request->integer('per_page', 30));

        return InvoiceClientResource::collection($clients);
    }

    public function store(StoreInvoiceClientRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $client = InvoiceClient::create([
            ...$request->validated(),
            'created_by_user_id' => $user->id,
        ]);

        return response()->json([
            'data' => new InvoiceClientResource($client),
            'message' => 'Client created.',
        ], 201);
    }

    public function show(InvoiceClient $invoiceClient): JsonResponse
    {
        $this->authorize('view', $invoiceClient);

        return response()->json(['data' => new InvoiceClientResource($invoiceClient)]);
    }

    public function update(UpdateInvoiceClientRequest $request, InvoiceClient $invoiceClient): JsonResponse
    {
        $invoiceClient->update($request->validated());

        return response()->json([
            'data' => new InvoiceClientResource($invoiceClient),
            'message' => 'Client updated.',
        ]);
    }

    public function destroy(InvoiceClient $invoiceClient): JsonResponse
    {
        $this->authorize('delete', $invoiceClient);

        if ($invoiceClient->invoices()->exists()) {
            return response()->json(['message' => 'Client has invoices and cannot be deleted.'], 422);
        }

        $invoiceClient->delete();

        return response()->json(['message' => 'Client deleted.']);
    }
}
