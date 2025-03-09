<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\DishStatus;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderItemController extends Controller
{
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

    public function setPreparingStatus(Request $request)
    {
        try {
            Log::info($request->all());
            OrderItem::whereIn('id', $request['ids'])->update(['status_id' => $request['status_id']]);
    
            return ApiResponse::success([], 'Se ha notificado al chef', 200);
        } catch (\Throwable $th) {
            return ApiResponse::error('Error interno al actualizar la orden', 500);
        }
    }
}
