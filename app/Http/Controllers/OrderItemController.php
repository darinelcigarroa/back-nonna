<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\DishStatus;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\OrderItemsUpdated;
use Illuminate\Support\Facades\Log;
use App\Actions\UpdateOrderItemStatusAction;

class OrderItemController extends Controller
{
    public function __construct(protected UpdateOrderItemStatusAction $updateOrderItemStatusAction) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        try {
            $orderItem->delete();

            return ApiResponse::success([], 'Se ha notificado al chef', 200);
        } catch (\Throwable $th) {
            return ApiResponse::error('Error interno al eliminar la orden', 500);
        }
    }

    public function updateDishStatus(Request $request)
    {
        try {
            $result = $this->updateOrderItemStatusAction->execute(
                $request->input('orderItems'),
                $request->input('status_id')
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 409);
            }

            return ApiResponse::success(
                $result['data'],
                $result['message']
            );

        } catch (\Throwable $th) {
            return ApiResponse::error('Error interno al actualizar el platillo', 500);
        }
    }    
    
}
