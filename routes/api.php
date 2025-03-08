<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\DishTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\TableController;

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);

    Route::prefix('catalogs')->group(function () {
        Route::resource('tables', TableController::class)->except('create', 'edit', 'show');
        Route::resource('dish-types', DishTypeController::class)->except('create', 'edit', 'show');
    });

    Route::prefix('waiter')->middleware('role:waiter')->group(function () {
        Route::resource('orders', OrderController::class)->except('create', 'show');
        Route::resource('order-item', OrderItemController::class)->except('create', 'show');
        Route::resource('dishes', DishController::class)->except('create', 'edit', 'show');
    });
});

