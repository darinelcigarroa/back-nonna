<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    public const PENDING = 1;
    public const COMPLETED = 2;
    public const SHIPPED = 3;
    public const PAID = 4;
    public const EDITING = 5;
    public const CANCELED = 6;
}
