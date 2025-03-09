<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    CONST STATUS_CREATE = 1;
    CONST STATUS_IN_KITCHEN = 2;
    CONST STATUS_IN_PREPARATION = 3;
    CONST STATUS_READY_TO_SERVE = 4;
    CONST STATUS_CANCELED = 5;

    protected $fillable = [
        'dish_id',
        'quantity',
        'price',
        'dish_name',
        'observations',
        'status_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function dish() {
        return $this->belongsTo(Dish::class);
    }
    
    // Este estatus es el del OrderItem, no el del platillos dish_statuses
    public function orderItemStatus()
    {
        return $this->belongsTo(DishStatus::class, 'status_id');
    }
}
