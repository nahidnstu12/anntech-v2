<?php

namespace App\Models;

use App\Support\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_client_id',
        'created_by_user_id',
        'billing_user_id',
        'number',
        'type',
        'status',
        'issue_date',
        'due_date',
        'currency',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'notes_public',
        'notes_internal',
        'sent_at',
        'paid_at',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'sent_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(InvoiceClient::class, 'invoice_client_id');
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(InvoiceLineItem::class)->orderBy('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function billingUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'billing_user_id');
    }

    public function isDraft(): bool
    {
        return $this->status === InvoiceStatus::DRAFT;
    }

    public function isEditable(): bool
    {
        return $this->status === InvoiceStatus::DRAFT;
    }

    public function displayNumber(): string
    {
        return $this->number ?? 'DRAFT-'.$this->id;
    }
}
