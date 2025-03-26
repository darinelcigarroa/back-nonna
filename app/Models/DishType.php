<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishType extends Model
{
    /** @use HasFactory<\Database\Factories\DishTypeFactory> */
    use HasFactory;

    public function dishes ()
    {
        return $this->hasMany(Dish::class);
    }
}
