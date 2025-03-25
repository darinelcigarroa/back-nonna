<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Table;
use App\Models\Employee;
use App\Models\OrderStatus;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    public function getStats()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $currentMonthTotal = Order::where('order_status_id', OrderStatus::PAID)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_amount');

        $totalDishesMonth = OrderItem::whereHas('order', function ($query) use ($month, $year) {
            $query->where('order_status_id', OrderStatus::PAID)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
        })->sum('quantity');

        return ApiResponse::success([
            // Dishes
            'totalDishes' => Dish::count(),
            'dishesConsumed' => Dish::where('stock_status', 'out_of_stock')->count(),
            // Clents
            'totalDinners' => Table::sum('capacity'),
            'dinnersConsumed' => Order::where('order_status_id', OrderStatus::PENDING)->sum('num_dinners'),
            // Tables
            'totalTables' => Table::count(),
            'tablesConsumed' => Table::where('in_use', true)->count(),
            // Sales
            'currentMonthTotal' => $currentMonthTotal,
            // total number of dishes sold
            'totalDishesMonth' => $totalDishesMonth,
            // Employees
            'totalEmployees' => Employee::count(),
            // total canceled orders
            'totalCanceledOrders' => Order::where('order_status_id', OrderStatus::CANCELED)->count(),
        ]);
    }
}
