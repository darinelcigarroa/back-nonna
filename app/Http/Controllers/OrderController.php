<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Helpers\ApiResponse;
use App\Models\OrderItem;
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
            $orders = Order::with(['table'])->select(
                'id',
                'folio',
                'num_dinners',
                'status',
                'table_id',
                'total_amount',
                'created_at'
            )->get();

            return ApiResponse::success([
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
                'table_id' => $request->table_id,
                'num_dinners' => $request->num_dinners,
                'user_id' => Auth::id(),
                'status' => Order::STATUS_PENDING,
                'total_amount' => $total,
            ]);

            Log::info('ok', $request->orders);
            foreach ($request->orders as $item) {

                $orderItem = $order->orderItems()->create([
                    'dish_id' => $item['dish']['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['dish']['price'],
                    'dish_name' => $item['dish']['name'],
                    'dish_name' => $item['dish']['name'],
                    'observations' => $item['observations'] ?? null,
                    'status_id' => OrderItem::STATUS_IN_KITCHEN,
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
    public function edit(Order $order)
    {
        try {
            $order->loadMissing([
                'orderItems:id,quantity,observations,order_id,status_id,dish_id',
                'orderItems.dish:id,name,dish_type_id,price', // Asegúrate de incluir `dish_type_id` si es clave foránea
                'orderItems.dish.dishType:id,name', // Cargar la relación `typeDish`
                'table:id,capacity,name'
            ]);

            $orderData = [
                'orderID' => $order->id,
                'numberDiners' => $order->num_dinners,
                'table' => $order->table,
                'quantity' => 1,
                'folio' => $order->folio,
                'date' => $order->formatted_date,
                'time' => $order->formatted_time,
            ];

            return ApiResponse::success(['order' => $orderData, 'orderItems' => $order->orderItems]);
        } catch (\Exception $e) {
            return ApiResponse::error('Error interno al obtener la orden', 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            foreach ($request->orders as $item) {
                $order->orderItems()->updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'dish_id' => $item['dish']['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['dish']['price'],
                        'dish_name' => $item['dish']['name'],
                        'observations' => $item['observations'] ?? null,
                        'status_id' => $item['status_id'] ==   OrderItem::STATUS_CREATE  ?  OrderItem::STATUS_IN_KITCHEN :  $item['status_id'],
                    ]
                );
            }
    
            DB::commit();
    
            return ApiResponse::success([
                'orderItems' => $order->orderItems
            ], 'OrderItems actualizados correctamente', 200);
    
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return ApiResponse::error('Error interno al actualizar los OrderItems', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        try {
            
            $order->delete();
            return ApiResponse::success(['message' => 'Orden eliminada correctamente']);
        } catch (Exception $e) {
            Log::error($e);
            return ApiResponse::error('Error interno al eliminar la orden', 500);
        }
    }
}
