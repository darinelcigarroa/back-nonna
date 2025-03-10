<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChefOrderController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(Request $request)
    {
        try {
            
            $this->authorize('viewAny', Order::class);

            $orders = $this->orderService->getOrders(
                $request->input('per_page'),
                true // ✅ Chef NO debe ver items completados (status_id = 4)
            );

            // ✅ Aplicar lógica de presentación para el chef
            $orders->getCollection()->transform(function ($order) {
                $order->selectAll = false;

                $order->setRelation('orderItems', $order->orderItems->map(function ($item) {
                    $item->checked = false;
                    return $item;
                }));

                return $order;
            });

            return ApiResponse::success([
                'orders' => $orders
            ]);

        } catch (\Throwable $th) {
            return ApiResponse::error('Error interno al obtener las órdenes', 500);
        }
    }
}
