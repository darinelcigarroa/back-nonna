<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    if ($user->id === (int) $id) {
        return true;
    }
    return false;
});


Broadcast::channel('orders', function ($user) {
    return true;
});
