<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_PENDING = 1;
    const STATUS_SENT = 2;
    const STATUS_PAID = 3;
    const STATUS_EDIT = 4;
    const STATUS_CANCELED = 5;

    protected $fillable = [
        'folio',
        'num_dinners',
        'order_status_id',
        'user_id',
        'table_id',
        'total_amount',
    ];

    protected $appends = ['formatted_date', 'formatted_time'];

    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d') : null;
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at ? $this->created_at->format('H:i:s A') : null;
    }

    public static function generateUniqueFolio()
    {
        return DB::transaction(function () {
            // Bloquea la consulta para evitar colisiones
            $lastFolio = DB::table('orders')
                ->lockForUpdate()
                ->latest('id')
                ->value('folio');

            // Si no hay registros, inicia desde "ORD-0001"
            $number = $lastFolio ? (int) substr($lastFolio, 4) + 1 : 1;

            return 'ORD-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
}
