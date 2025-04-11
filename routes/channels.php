<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    if ($user->id === (int) $id) {
        return true;
    }
    return false;
});

Broadcast::channel('test-channel', function ($user) {
    // Autenticación del canal
    Log:info('trasnmitiendo al canal test-channel');
    return true;  // Esto es solo un ejemplo, puedes agregar más lógica para determinar si el usuario tiene acceso
});

Broadcast::channel('waiter-editing-order', function ($user) {
    return $user->hasRole(['waiter', 'chef']);
});

Broadcast::channel('order-items-updated', function ($user) {
    return $user->hasRole(['chef', 'admin', 'waiter']);
});

Broadcast::channel('orders-updated', function ($user) {
    return $user->hasRole(['chef']);
});

Broadcast::channel('order-item-deleted', function ($user) {
    return $user->hasRole(['chef', 'waiter']);
});
