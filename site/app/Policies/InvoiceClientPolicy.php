<?php

namespace App\Policies;

use App\Models\InvoiceClient;
use App\Models\User;

class InvoiceClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-invoice-clients')
            || $user->can('create-invoice')
            || $user->can('view-all-invoices')
            || $user->can('view-own-invoices');
    }

    public function view(User $user, InvoiceClient $invoiceClient): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('manage-invoice-clients');
    }

    public function update(User $user, InvoiceClient $invoiceClient): bool
    {
        return $user->can('manage-invoice-clients');
    }

    public function delete(User $user, InvoiceClient $invoiceClient): bool
    {
        return $user->can('manage-invoice-clients');
    }
}
