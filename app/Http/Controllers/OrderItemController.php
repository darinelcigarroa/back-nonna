<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Events\OrderItemDeleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Actions\UpdateOrderItemStatusAction;
use App\Http\Requests\DeleteOrderItemRequest;

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
    public function destroy(Request $request, OrderItem $orderItem)
    {
        try {
              
            if ($orderItem->order->orderItems->count() <= 1) {
                return ApiResponse::error('La orden debe de tener al menos un platillo', 403);
            }
        
            $orderItem->delete();
            broadcast(new OrderItemDeleted($orderItem->only(['id', 'order_id'])));

            return ApiResponse::success([], 'Se ha eliminado el platillo', 200);
        } catch (\Throwable $th) {
            Log::info($th);
            return ApiResponse::error('Error interno al eliminar el platillo', 500);
        }
    }

    public function updateDishStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            Log::info('updateDishStatus');
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
            DB::commit();
            return ApiResponse::success(
                $result['data'],
                $result['message']
            );

        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return ApiResponse::error('Error interno al actualizar el platillo', 500);
        }
    }    
    
}
