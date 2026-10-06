<?php

namespace App\Http\Requests\Api\Admin;

use App\Support\InquiryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-contact-inquiries') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'required', Rule::in(InquiryStatus::all())],
            'internal_notes' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'assigned_to_user_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
        ];
    }
}
