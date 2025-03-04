<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::all();
          
            ApiResponse::success([
                'orders' => $orders,
            ]);

        } catch (Exception $e) {
            return ApiResponse::error('Error interno al obtener las ordenes', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $folio = Order::generateUniqueFolio();
            $total = 0;
    
            $order = Order::create([
                'folio' => $folio,
                'table_id' => $request->table['id'],
                'num_dinners' => $request->numberDiners,
                'user_id' => Auth::id(),
                'status' => Order::STATUS_PENDING,
                'total_amount' => $total, 
            ]);
    
            foreach ($request->orders as $item) {
                $orderItem = $order->orderItems()->create([
                    'dish_id' => $item['typeDish']['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['dishe']['price'],
                    'dish_name' => $item['dishe']['name'],
                    'observations' => $item['observations'],
                ]);
    
                $total += $orderItem->price * $orderItem->quantity;
            }
    
            $order->update(['total_amount' => $total]);
    
            DB::commit();
    
            return ApiResponse::success([
                'order' => $order
            ], 'Orden creada correctamente', 201);
    
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return ApiResponse::error('Error interno al enviar las orden a cocina', 500);
        }
    }    

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }
}
