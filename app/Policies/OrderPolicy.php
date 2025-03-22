<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderPolicy
{
    // ✅ Permitir a waiter y chef ver el índice
    public function viewAny(User $user)
    {
        return $user->hasRole('waiter') || $user->hasRole('chef') || $user->hasRole('super-admin');
    }

    // ✅ Solo permitir a waiter edite órdenes
    public function edit(User $user)
    {
        return $user->hasRole('waiter');
    }

    // ✅ Solo permitir a waiter crear órdenes
    public function create(User $user)
    {
        return $user->hasRole('waiter');
    }

    // ✅ Solo permitir a waiter actualizar órdenes
    public function update(User $user, Order $order)
    {
        return $user->hasRole('waiter');
    }

    // ✅ Solo permitir a waiter eliminar órdenes
    public function delete(User $user, Order $order)
    {
        return $user->hasRole('waiter');
    }
    // ✅ Solo permitir a waiter actualizar el estado de la orden
    public function payOrder(User $user, Order $order)
    {
        return $user->hasRole('waiter');
    }
}
