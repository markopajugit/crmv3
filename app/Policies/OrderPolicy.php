<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine if the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view orders
    }

    /**
     * Determine if the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        return true; // All authenticated users can view orders
    }

    /**
     * Determine if the user can create orders.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create orders
    }

    /**
     * Determine if the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        return true; // All authenticated users can update orders
    }

    /**
     * Determine if the user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        return true; // All authenticated users can delete orders
    }
}

