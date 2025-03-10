<?php

namespace App\Services;

use App\Models\Order;

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

        // ✅ Excluir órdenes donde todos los items están completados
        if ($excludeCompleted) {
            $query->whereHas('orderItems', function ($query) {
                $query->where('status_id', '<>', 4);
            });
        }

        return $query->simplePaginate($rowsPerPage);
    }
}
