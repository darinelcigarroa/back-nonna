<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisheController;
use App\Http\Controllers\DishTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);

    Route::prefix('catalogs')->group(function () {
        Route::resource('tables', TableController::class)->except('create', 'edit');
        Route::resource('dish-types', DishTypeController::class)->except('create', 'edit');
    });

    Route::prefix('waiter')->middleware('role:waiter')->group(function () {
        Route::resource('orders', OrderController::class)->except('create', 'edit');
        Route::resource('dishes', DisheController::class)->except('create', 'edit');
    });
});
