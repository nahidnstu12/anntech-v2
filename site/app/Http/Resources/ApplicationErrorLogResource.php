<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ApplicationErrorLog */
class ApplicationErrorLogResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'level' => $this->level,
            'exception_class' => $this->exception_class,
            'message' => $this->message,
            'file' => $this->file,
            'line' => $this->line,
            'stack_trace' => $this->when(
                $request->routeIs('admin.error-logs.show'),
                $this->stack_trace
            ),
            'request_id' => $this->request_id,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null,
            'http_method' => $this->http_method,
            'url' => $this->url,
            'route_name' => $this->route_name,
            'status_code' => $this->status_code,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'context' => $this->when(
                $request->routeIs('admin.error-logs.show'),
                $this->context
            ),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
