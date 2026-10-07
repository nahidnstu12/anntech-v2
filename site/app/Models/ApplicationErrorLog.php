<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationErrorLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'level',
        'exception_class',
        'message',
        'file',
        'line',
        'stack_trace',
        'request_id',
        'user_id',
        'http_method',
        'url',
        'route_name',
        'status_code',
        'ip_address',
        'user_agent',
        'context',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'context' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
