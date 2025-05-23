<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\DishTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentTypeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/api-test', function () {
    return response()->json(['message' => 'API SUCCESS']);
});

// routes/web.php
Route::post('/trigger-event', function (Request $request) {
    try {
        $msg = $request->input('message', 'Mensaje por defecto');
        
        Log::info('Emitido evento TestEvent con mensaje:', ['message' => $msg]);

        broadcast(new \App\Events\TestEvent($msg));

        return response()->json(['message' => 'Evento emitido']);
    } catch (\Exception $e) {
        Log::error('Error al emitir el evento:', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'Error al emitir evento'], 500);
    }
});

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
        Route::get('get-menu-dishes', [DishController::class, 'getMenuDishes']);
        Route::patch('toggle-dish-status/{dish}', [DishController::class, 'toggleDishStatus']);
    });

    Route::prefix('admin')->group(function () {
        Route::resource('employee', EmployeeController::class)->except('create', 'edit', 'show');
        Route::get('export/employee/excel', [EmployeeController::class, 'exportEmployeeExcel']);
        Route::get('export/order/excel', [OrderController::class, 'exportOrderExcel']);
        Route::get('get-most-used-tables', [ChartController::class, 'getMostUsedTables']);
        Route::get('get-monthly-income-trend', [ChartController::class, 'monthlyIncomeTrend']);
        Route::get('get-services-attended-waiter', [ChartController::class, 'servicesAttendedWaiter']);
        Route::get('get-trends-main-course-sales', [ChartController::class, 'trendsMainCourseSales']);
    });
    // Stats
    Route::get('dashboard/stats', [DashboardController::class, 'getStats']);
    // USER
    Route::resource('user', UserController::class);
    // ORDER
    Route::resource('orders', OrderController::class)->except('create', 'show');
    Route::post('orders/cancel-editing/{order}', [OrderController::class, 'cancelEditing']);
    Route::patch('pay-order/{order}', [OrderController::class, 'payOrder']);
    Route::patch('cancel-order/{order}', [OrderController::class, 'cancelOrder']);
    // ORDER ITEMS
    Route::resource('order-item', OrderItemController::class)->except('create', 'show');
    Route::patch('order-items/update-dish-status', [OrderItemController::class, 'updateDishStatus']);

    Route::get('phrase', function () {
        return ['phrase' => 'El éxito es la suma de pequeños esfuerzos repetidos cada día.', 'author' => 'Robert Collier'];
    });
});
