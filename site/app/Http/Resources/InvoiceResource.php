<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Invoice */
class InvoiceResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'display_number' => $this->displayNumber(),
            'number' => $this->number,
            'type' => $this->type,
            'status' => $this->status,
            'invoice_client_id' => $this->invoice_client_id,
            'client' => new InvoiceClientResource($this->whenLoaded('client')),
            'created_by_user_id' => $this->created_by_user_id,
            'billing_user_id' => $this->billing_user_id,
            'billing_user' => $this->whenLoaded('billingUser', fn () => [
                'id' => $this->billingUser->id,
                'name' => $this->billingUser->name,
            ]),
            'issue_date' => $this->issue_date?->format('Y-m-d'),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'currency' => $this->currency,
            'subtotal' => $this->subtotal,
            'tax_rate' => $this->tax_rate,
            'tax_amount' => $this->tax_amount,
            'total' => $this->total,
            'notes_public' => $this->notes_public,
            'notes_internal' => $this->notes_internal,
            'sent_at' => $this->sent_at?->toIso8601String(),
            'paid_at' => $this->paid_at?->toIso8601String(),
            'line_items' => InvoiceLineItemResource::collection($this->whenLoaded('lineItems')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
