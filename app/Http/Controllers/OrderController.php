<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Models\OrderItem;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Events\OrdersUpdated;
use App\Services\OrderService;
use App\Events\OrderItemsUpdated;
use App\Events\WaiterEditingOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function __construct(protected OrderService $orderService) {}

    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', Order::class);

            $orders = $this->orderService->getOrders(
                $request->input('per_page'),
                false // ✅ Mesero debe ver todos los items
            );

            return ApiResponse::success([
                'orders' => $orders
            ]);
        } catch (\Throwable $th) {
            return ApiResponse::error('Error interno al obtener las órdenes', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->authorize('create', Order::class);

            DB::beginTransaction();
            $folio = Order::generateUniqueFolio();
            $total = 0;

            $order = Order::create([
                'folio' => $folio,
                'table_id' => $request->table_id,
                'num_dinners' => $request->num_dinners,
                'user_id' => Auth::id(),
                'order_status_id' => Order::STATUS_PENDING,
                'total_amount' => $total,
            ]);

            foreach ($request->orders as $item) {

                $orderItem = $order->orderItems()->create([
                    'dish_id' => $item['dish']['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['dish']['price'],
                    'dish_name' => $item['dish']['name'],
                    'observations' => $item['observations'] ?? null,
                    'status_id' => OrderItem::STATUS_IN_KITCHEN,
                ]);

                $total += $orderItem->price * $orderItem->quantity;
            }

            $order->update(['total_amount' => $total]);
        
            $order->load([
                'orderItems.orderItemStatus',
                'table'
            ]);
            

            broadcast(new OrdersUpdated($order))->toOthers();

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
            $this->authorize('edit', Order::class);

            $order->update([
                'order_status_id' => Order::STATUS_EDIT
            ]);

            broadcast(new WaiterEditingOrder($order))->toOthers();

            $order->loadMissing([
                'orderItems:id,quantity,observations,order_id,dish_id,status_id',
                'orderItems.dish:id,name,dish_type_id,price',
                'orderItems.dish.dishType:id,name',
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
            Log::error($e);
            return ApiResponse::error('Error interno al obtener la orden', 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        try {
            // $this->authorize('update', Order::class);

            DB::beginTransaction();

            $updatedItems = [];

            foreach ($request->orders as $item) {
                $status = $item['status_id'] == OrderItem::STATUS_CREATE ? OrderItem::STATUS_IN_KITCHEN : $item['status_id'];
                $updatedItem = $order->orderItems()->updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'dish_id' => $item['dish']['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['dish']['price'],
                        'dish_name' => $item['dish']['name'],
                        'observations' => $item['observations'] ?? null,
                        'status_id' => $status
                    ]
                );

                $updatedItems[] = $updatedItem;
            }

            broadcast(new OrderItemsUpdated($order->id, $updatedItems, false))->toOthers();

            DB::commit();

            return ApiResponse::success([
                'orderItems' => $updatedItems
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
            $this->authorize('delete', Order::class);

            $order->delete();
            return ApiResponse::success(['message' => 'Orden eliminada correctamente']);
        } catch (Exception $e) {
            Log::error($e);
            return ApiResponse::error('Error interno al eliminar la orden', 500);
        }
    }

    public function cancelEditing(Order $order)
    {
        try {
            if ($order->order_status_id === Order::STATUS_EDIT) {

                $order->update([
                    'order_status_id' => Order::STATUS_PENDING
                ]);

                broadcast(new WaiterEditingOrder($order))->toOthers();

                return ApiResponse::success(null, 'Estado regresado a pendiente.');
            }

            return ApiResponse::success(null, 'No se requiere actualización.');
        } catch (\Exception $e) {
            Log::error($e);
            return ApiResponse::error('Error al cancelar edición, avisar al administrador', 500);
        }
    }
}
