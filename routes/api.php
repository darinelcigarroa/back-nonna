<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\DishTypeController;
use App\Http\Controllers\ChefOrderController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);

    Route::prefix('catalogs')->group(function () {
        Route::resource('table', TableController::class)->except('create', 'edit', 'show');
        Route::resource('dish-types', DishTypeController::class)->except('create', 'edit', 'show');
        Route::resource('positions', PositionController::class)->except('create', 'edit', 'show');
        Route::resource('dish', DishController::class)->except('create', 'show');
    });

    Route::prefix('admin')->group(function () {
        Route::resource('employee', EmployeeController::class)->except('create', 'edit', 'show');
    });
    // USER
    Route::resource('user', UserController::class);
    // ORDER
    Route::resource('orders', OrderController::class)->except('create', 'show');
    Route::post('orders/cancel-editing/{order}', [OrderController::class, 'cancelEditing']);
    // ORDER ITEMS
    Route::resource('order-item', OrderItemController::class)->except('create', 'show');
    Route::patch('order-items/update-dish-status', [OrderItemController::class, 'updateDishStatus']);
    // CHEF
    Route::get('chef/orders', [ChefOrderController::class, 'index']);
});
