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

}
