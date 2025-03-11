<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\OrderItem;
use App\Events\OrderItemsUpdated;

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

    public function updateOrderItems($ids, $statusId)
    {
        OrderItem::whereIn('id', $ids)->update(['status_id' => $statusId]);
    }

    public function getUpdatedItems($ids, $orderID)
    {
        $completed = !OrderItem::where('order_id', $orderID)
            ->where('status_id', '!=', OrderItem::STATUS_READY_TO_SERVE)
            ->exists();

        return [
            'completed' => $completed,
            'ordersItem' => OrderItem::with('orderItemStatus')->whereIn('id', $ids)->get()
        ];
    }

    public function broadcastOrderUpdate($updatedItems)
    {
        $orderItems = $updatedItems['ordersItem'];
        broadcast(new OrderItemsUpdated($orderItems->first()->order_id, $orderItems->toArray(), $updatedItems['completed']));
    }
}
