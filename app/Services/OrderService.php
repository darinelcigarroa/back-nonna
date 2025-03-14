<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function getOrders($rowsPerPage, $chef = false)
    {
        $userId = Auth::id();

        $query = Order::with(['table', 'orderItems' => function ($query) {
            $query->select(
                'id',
                'dish_id',
                'quantity',
                'dish_name',
                'dish_type',
                'observations',
                'status_id',
                'order_id',
                'updated_at'
            )
                ->with(['orderItemStatus:id,name'])
                ->orderBy('id', 'ASC');
        }])
            ->when($chef, function ($query) {
                $query->where('order_status_id', '!=', OrderStatus::COMPLETED);
            })
            ->when(!$chef, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->select(
                'id',
                'folio',
                'order_status_id',
                'table_id',
                'total_amount',
                'created_at'
            )
            ->orderBy('id', 'ASC');

        return $query->simplePaginate($rowsPerPage);
    }

    public function pendingOrdersCount()
    {
        return Order::where('order_status_id', '!=', OrderStatus::COMPLETED)->count();
    }
}
