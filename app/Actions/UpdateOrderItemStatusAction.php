<?php

namespace App\Actions;

use App\Services\OrderItemService;
use Illuminate\Support\Facades\Log;

class UpdateOrderItemStatusAction
{
    public function __construct(protected OrderItemService $orderItemService) {}

    public function execute(array $orderItems, int $statusId)
    {
        $conflicts = $this->orderItemService->validateConflicts($orderItems);

        if (!empty($conflicts)) {
            $conflictMessage = implode(', ', $conflicts);
            return [
                'success' => false,
                'message' => "El estado de los platillos {$conflictMessage} fue actualizado por otro chef"
            ];
        }

        $ids = array_column($orderItems, 'id');

        $orderID = (int) array_unique(array_column($orderItems, 'order_id'))[0];

        $completed = $this->orderItemService->updateOrderItems($ids, $statusId, $orderID);

        $updatedItems = $this->orderItemService->getUpdatedItems($ids);
        
        $this->orderItemService->broadcastOrderUpdate($orderID, $updatedItems, $completed);

        $this->orderItemService->broadcastPendingOrdersCountUpdated();

        return [
            'success' => true,
            'data' => $updatedItems,
            'message' => count($updatedItems) > 1 
                ? 'Se ha notificado el estado de los platillos'
                : 'Se ha notificado el estado del platillo'
        ];
    }
}
