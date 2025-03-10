<?php

namespace App\Actions;

use App\Services\OrderItemService;

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

        $this->orderItemService->updateOrderItems($ids, $statusId);

        $updatedItems = $this->orderItemService->getUpdatedItems($ids);

        $this->orderItemService->broadcastOrderUpdate($updatedItems);

        return [
            'success' => true,
            'data' => $updatedItems,
            'message' => count($updatedItems) > 1 
                ? 'Se ha notificado el estado de los platillos'
                : 'Se ha notificado el estado del platillo'
        ];
    }
}
