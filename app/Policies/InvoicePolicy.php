<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    /**
     * Determine if the user can view any invoices.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view invoices
    }

    /**
     * Determine if the user can view the invoice.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        return true; // All authenticated users can view invoices
    }

    /**
     * Determine if the user can create invoices.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create invoices
    }

    /**
     * Determine if the user can update the invoice.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        return true; // All authenticated users can update invoices
    }

    /**
     * Determine if the user can delete the invoice.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        return true; // All authenticated users can delete invoices
    }
}

