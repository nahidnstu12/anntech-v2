<?php

namespace App\Http\Requests\Api\Admin;

use App\Support\AdminRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-roles') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')->where('guard_name', 'web')->ignore($roleId),
                Rule::notIn([AdminRoles::SUPER_ADMIN]),
            ],
        ];
    }
}
