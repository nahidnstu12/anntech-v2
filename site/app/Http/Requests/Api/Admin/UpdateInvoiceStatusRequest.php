<?php

namespace App\Http\Requests\Api\Admin;

use App\Support\InvoiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $invoice = $this->route('invoice');

        return $invoice && $this->user()?->can('updateStatus', $invoice);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([
                InvoiceStatus::PAID,
                InvoiceStatus::OVERDUE,
                InvoiceStatus::CANCELLED,
            ])],
        ];
    }
}
