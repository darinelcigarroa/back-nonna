<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['name', 'status', 'capacity'];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'ILIKE', "%$search%")
            ->orWhere('status', 'ILIKE', "%$search%")
            ->orWhere('capacity', 'ILIKE', "%$search%");
    }
}
