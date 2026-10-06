<?php

namespace App\Http\Requests\Api\Admin;

use App\Support\InvoiceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create-invoice') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'invoice_client_id' => ['required', 'integer', 'exists:invoice_clients,id'],
            'billing_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'type' => ['required', Rule::in(InvoiceType::all())],
            'issue_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'currency' => ['nullable', 'string', 'size:3'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes_public' => ['nullable', 'string', 'max:5000'],
            'notes_internal' => ['nullable', 'string', 'max:10000'],
            'line_items' => ['required', 'array', 'min:1'],
            'line_items.*.description' => ['required', 'string', 'max:2000'],
            'line_items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'line_items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
