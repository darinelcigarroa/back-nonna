<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;
    
    protected $fillable = [
        'name',
        'first_surname',
        'second_surname',
        'position_id',
        'salary'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function user() {
        return $this->hasOne(User::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'ILIKE', "%$search%")
            ->orWhere('first_surname', 'ILIKE', "%$search%")
            ->orWhere('second_surname', 'ILIKE', "%$search%")
            ->orWhere('salary', 'ILIKE', "%$search%")
            ->orWhereHas('position', function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%$search%");
            });
    }

}
