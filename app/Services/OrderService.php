<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function getOrders($rowsPerPage, $excludeCompleted = false)
    {
        $query = Order::with(['table', 'orderItems' => function ($query)  {
            $query->select(
                'id',
                'dish_id',
                'quantity',
                'price',
                'dish_name',
                'observations',
                'status_id',
                'order_id',
                'updated_at'
            )
                ->with('orderItemStatus')
                ->orderBy('id', 'ASC');
        }])
            ->when($excludeCompleted, function ($query) {
                $query->where('order_status_id', '!=', OrderStatus::COMPLETED);
            })
            ->select(
                'id',
                'folio',
                'num_dinners',
                'order_status_id',
                'table_id',
                'total_amount',
                'created_at'
            )
            ->orderBy('id', 'ASC');

        return $query->simplePaginate($rowsPerPage);
    }

    public function pendingOrdersCount() {
       return Order::where('order_status_id', '!=', OrderStatus::COMPLETED)->count();
    }
}
