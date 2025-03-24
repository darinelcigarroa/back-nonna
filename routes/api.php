<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\DishTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ChefOrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentTypeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);

    Route::prefix('catalogs')->group(function () {
        Route::resource('table', TableController::class)->except('create', 'edit', 'show');
        Route::get('get-tables', [TableController::class, 'getTables']);
        Route::resource('payment-types', PaymentTypeController::class)->except('create', 'edit', 'show');
        Route::get('get-payment-types',[PaymentTypeController::class, 'getPaymentTypes']);
        Route::resource('positions', PositionController::class)->except('create', 'edit', 'show');
        Route::resource('dish-types', DishTypeController::class)->except('create', 'edit', 'show');
        Route::resource('dish', DishController::class)->except('create', 'show');
        Route::get('get-dishes', [DishController::class, 'getDishes']);
    });

    Route::prefix('admin')->group(function () {
        Route::resource('employee', EmployeeController::class)->except('create', 'edit', 'show');
        Route::get('get-most-used-tables', [ChartController::class, 'getMostUsedTables']);
        Route::get('get-monthly-income-trend', [ChartController::class, 'monthlyIncomeTrend']);
        Route::get('get-services-attended-waiter', [ChartController::class, 'servicesAttendedWaiter']);
        Route::get('get-trends-main-course-sales', [ChartController::class, 'trendsMainCourseSales']);
    });
    // USER
    Route::resource('user', UserController::class);
    // ORDER
    Route::resource('orders', OrderController::class)->except('create', 'show');
    Route::post('orders/cancel-editing/{order}', [OrderController::class, 'cancelEditing']);
    Route::patch('pay-order/{order}', [OrderController::class, 'payOrder']);
    // ORDER ITEMS
    Route::resource('order-item', OrderItemController::class)->except('create', 'show');
    Route::patch('order-items/update-dish-status', [OrderItemController::class, 'updateDishStatus']);

    Route::get('/phrase', function () {
        return ['phrase' => 'El éxito es la suma de pequeños esfuerzos repetidos cada día.', 'author' => 'Robert Collier'];
    });
});
