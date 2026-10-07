<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Spatie\Permission\Models\Role */
class AdminRoleResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_system' => $this->name === \App\Support\AdminRoles::SUPER_ADMIN,
            'permissions' => $this->permissions->pluck('name')->sort()->values(),
            'users_count' => $this->whenCounted('users'),
        ];
    }
}
