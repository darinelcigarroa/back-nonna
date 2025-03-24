<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function getOrders($rowsPerPage, $filters = [], $search = null)
    {  
        $user = User::find(Auth::id());
        $isAdmin = $user->hasRole('super-admin');
        $isChef = $user->hasRole('chef');
        $isWaiter = $user->hasRole('waiter');
     
        $query = Order::with(['table', 'orderStatus:id,name', 'orderItems' => function ($query) {
            $query->select(
                'id',
                'dish_id',
                'quantity',
                'dish_name',
                'dish_type',
                'observations',
                'status_id',
                'order_id',
                'updated_at',
                'price',
            )
            ->with(['orderItemStatus:id,name'])
            ->orderBy('id', 'ASC');
        }])->when(!empty($search), function ($query) use ($search) {
            $query->search($search);
        })
        ->when(!empty($filters), function ($query) use ($filters) {
            $query->whereHas('orderStatus', function ($query) use ($filters) {
                $query->whereIn('name', $filters);
            });
        })
        ->when($isAdmin, function ($query) {
            $query->whereIn('order_status_id', [OrderStatus::PAID, OrderStatus::CANCELED]);
        })
        ->when($isChef, function ($query) {
            $query->where('order_status_id', '!=', OrderStatus::COMPLETED);
        })
        ->when($isWaiter, function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('order_status_id', '!=', OrderStatus::PAID);
        })
        ->select(
            'id',
            'folio',
            'order_status_id',
            'table_id',
            'total_amount',
            'editing',
            'created_at',
            'updated_at',
        )
        ->orderBy('updated_at', 'ASC');
    
        return $query->paginate($rowsPerPage);
    }
    
    public function pendingOrdersCount()
    {
        return Order::where('order_status_id', '!=', OrderStatus::COMPLETED)->count();
    }
}
