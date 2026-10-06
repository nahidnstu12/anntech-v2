<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use App\Support\InvoiceStatus;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-all-invoices')
            || $user->can('view-own-invoices')
            || $user->can('create-invoice');
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if ($user->can('view-all-invoices')) {
            return true;
        }

        if ($user->can('view-own-invoices') || $user->can('create-invoice')) {
            return $invoice->created_by_user_id === $user->id
                || $invoice->billing_user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create-invoice');
    }

    public function update(User $user, Invoice $invoice): bool
    {
        if (! $user->can('edit-invoice')) {
            return false;
        }

        if (! $this->view($user, $invoice)) {
            return false;
        }

        return $invoice->isEditable();
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->can('delete-invoice-draft')
            && $invoice->isDraft()
            && $this->view($user, $invoice);
    }

    public function send(User $user, Invoice $invoice): bool
    {
        return $user->can('send-invoice')
            && $invoice->isDraft()
            && $this->view($user, $invoice);
    }

    public function updateStatus(User $user, Invoice $invoice): bool
    {
        if (! $user->can('edit-invoice') && ! $user->can('send-invoice')) {
            return false;
        }

        if (! $this->view($user, $invoice)) {
            return false;
        }

        return in_array($invoice->status, [InvoiceStatus::SENT, InvoiceStatus::OVERDUE], true);
    }
}
