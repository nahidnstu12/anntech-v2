<?php

namespace App\Http\Requests\Api\Admin;

use App\Support\InvoiceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $invoice = $this->route('invoice');

        return $invoice && $this->user()?->can('update', $invoice);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'invoice_client_id' => ['sometimes', 'required', 'integer', 'exists:invoice_clients,id'],
            'billing_user_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'type' => ['sometimes', 'required', Rule::in(InvoiceType::all())],
            'issue_date' => ['sometimes', 'nullable', 'date'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'currency' => ['sometimes', 'nullable', 'string', 'size:3'],
            'tax_rate' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'notes_public' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'notes_internal' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'line_items' => ['sometimes', 'required', 'array', 'min:1'],
            'line_items.*.description' => ['required_with:line_items', 'string', 'max:2000'],
            'line_items.*.quantity' => ['required_with:line_items', 'numeric', 'min:0.01'],
            'line_items.*.unit_price' => ['required_with:line_items', 'numeric', 'min:0'],
        ];
    }
}
