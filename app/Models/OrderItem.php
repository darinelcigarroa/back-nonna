<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dish_id',
        'quantity',
        'price',
        'dish_name',
        'observations',
        'status_id'
    ];

    protected $appends = ['checked'];

    public function getCheckedAttribute()
    {
        return $this->attributes['checked'] ?? false;
    }

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
        return $this->belongsTo(OrderItemStatus::class, 'status_id');
    }
}
