<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'dish_type_id'
    ];
    public function dishType()
    {
        return $this->belongsTo(DishType::class);
    }
}
