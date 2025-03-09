<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DishStatus extends Model
{
    public const STATUS_CREATED = 1; // Creado
    public const STATUS_IN_KITCHEN = 2; // En cocina
    public const STATUS_PREPARING = 3; // En Preparación
    public const STATUS_READY_TO_SERVE = 4; // Listo para Servir
    public const STATUS_CANCELED = 5; // Cancelado

    protected $fillable = [
        'name'
    ]; 
}
