<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'dish_id',
        'quantity',
        'price',
        'dish_name',
        'observations',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
