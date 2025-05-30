<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Events\OrdersUpdated;
use App\Models\OrderItemStatus;
use App\Events\OrderItemsUpdated;
use Illuminate\Support\Facades\Log;

class OrderItemService
{
    public function validateConflicts($incomingItems)
    {
        $ids = array_column($incomingItems, 'id');
        $orderItems = OrderItem::whereIn('id', $ids)->get()->keyBy('id');

        $conflicts = [];

        foreach ($incomingItems as $item) {
            $orderItem = $orderItems[$item['id']] ?? null;

            if ($orderItem) {
                if ($orderItem->updated_at->ne(Carbon::parse($item['updated_at']))) {
                    $conflicts[] = $orderItem->dish_name;
                }
            }
        }

        return $conflicts;
    }

    public function updateOrderItems($ids, $statusId, $orderID)
    {
        // Actualiza los elementos de la orden
        OrderItem::whereIn('id', $ids)->update(['status_id' => $statusId]);
        // Verifica directamente si hay elementos pendientes
        $pendingItems = !OrderItem::where('order_id', $orderID)
            ->where('status_id', '!=', OrderItemStatus::STATUS_READY_TO_SERVE)
            ->exists();

        Log::info('', ['ids' => $ids, 'pendingItems' => $pendingItems, 'orderID' => $orderID]);


        // Si no hay elementos pendientes, actualiza el estado de la orden
        if ($pendingItems) {
            Order::where('id', $orderID)->update(['order_status_id' => OrderStatus::COMPLETED]);
        }
        
        return $pendingItems;
    }


    public function getUpdatedItems($ids)
    {
        return OrderItem::with('orderItemStatus:id,name')->whereIn('id', $ids)->get()->toArray();
    }

    public function broadcastOrderUpdate($orderID, $orderItems, $completed)
    {
        broadcast(new OrderItemsUpdated($orderID, $orderItems, $completed));
    }

    public function broadcastPendingOrdersCountUpdated()
    {
        Log::info('Broadcasting OrdersUpdated');
        broadcast(new OrdersUpdated());
    }
}
