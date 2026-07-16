<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('orders.view');
    }

    public function view(User $user, Order $order): bool
    {
        // Must have view permission, and the order must belong to the active context (handled by scope)
        // OR the user is the customer
        return $user->hasPermission('orders.view') || $user->id === $order->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('tickets.purchase');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->hasPermission('orders.manage');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasPermission('orders.manage');
    }
}
