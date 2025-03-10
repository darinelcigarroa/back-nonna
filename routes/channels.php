<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    if ($user->id === (int) $id) {
        return true;
    }
    return false;
});

Broadcast::channel('order-items-updated', function ($user) {
    return $user->hasRole(['chef', 'admin', 'waiter']);
});

Broadcast::channel('waiter-editing-order.{orderId}', function ($user) {
    return $user->hasRole(['waiter']);
});
