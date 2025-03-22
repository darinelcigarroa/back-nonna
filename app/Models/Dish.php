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
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'ILIKE', "%$search%")
            ->orWhere('description', 'ILIKE', "%$search%")
            ->orWhere('price', 'ILIKE', "%$search%")
            ->orWhere('status', 'ILIKE', "%$search%");
    }
    public function dishType()
    {
        return $this->belongsTo(DishType::class);
    }
}
