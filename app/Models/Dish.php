<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    public function dishType()
    {
        return $this->belongsTo(DishType::class);
    }
}
