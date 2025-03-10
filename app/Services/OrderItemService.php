<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Events\OrderItemsUpdated;
use Carbon\Carbon;

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

    public function getUpdatedItems($ids)
    {
        return OrderItem::with('orderItemStatus')->whereIn('id', $ids)->get();
    }

    public function broadcastOrderUpdate($updatedItems)
    {
        broadcast(new OrderItemsUpdated($updatedItems->first()->order_id, $updatedItems->toArray()));
    }
}
